<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Store;
use App\Models\Supplier;

use Illuminate\Support\Facades\DB;
use Exception;

use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. --- BASE QUERY & PERMISSIONS ---
        $query = Order::with(['store', 'supplier']);

        // Variable to store authorized store IDs
        $allowedStoreIds = [];

        if ($user->role === 'admin') {
            // Get the IDs of the stores assigned to this Admin
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();

            if (!empty($allowedStoreIds)) {
                // Restrict query to only assigned stores
                $query->whereIn('store_id', $allowedStoreIds);
            } else {
                // If Admin has no stores assigned, hide everything
                $query->where('id', 0);
            }
        }

        $query->where('status', '!=', 'completed');

        // 2. --- FILTERS ---

        // Filter by Store
        if ($request->filled('store_id')) {
            // Security Check: Ensure the user is authorized for this store
            if ($user->role === 'super_admin' || in_array($request->store_id, $allowedStoreIds)) {
                $query->where('store_id', $request->store_id);
            }
        }

        // Filter by Supplier
        if ($request->filled('supplier_id')) {
            $query->where('Supplier_id', $request->supplier_id);
        }

        // Filter by Customer Name
        if ($request->filled('customer_name')) {
            $customerName = trim($request->customer_name);
            if ($customerName !== '') {
                $query->where('customer_name', 'like', '%' . $customerName . '%');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. --- SORTING ---
        // Default sorting logic:
        // 1. Orders in Extra Time (High Delay) first
        // 2. Orders in Main Time
        // 3. Oldest Orders
        $query->orderBy('days_spent_extra', 'desc')
            ->orderBy('days_spent_main', 'desc')
            ->orderBy('order_date', 'asc');

        // 4. --- GET DATA ---
        $orders = $query->paginate(50)->withQueryString();

        // 5. --- PREPARE DROPDOWNS (Smart Filters) ---
        if ($user->role === 'admin') {
            // Stores: Only show stores assigned to this admin
            $stores = $user->stores;

            // Suppliers: Only show suppliers linked to these stores
            $suppliers = Supplier::whereHas('orders', function ($q) use ($allowedStoreIds) {
                $q->whereIn('store_id', $allowedStoreIds);
            })->orderBy('first_name')->get();
        } else {
            // Super Admin: Show all
            $stores = Store::orderBy('name')->get();
            $suppliers = Supplier::orderBy('first_name')->get();
        }

        return view('admin.orders.index', compact('orders', 'suppliers', 'stores'));
    }

    public function completed(Request $request)
    {
        $user = auth()->user();
        $query = Order::with(['store', 'supplier'])->where('status', 'completed');
        $allowedStoreIds = [];

        if ($user->role === 'admin') {
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();

            if (!empty($allowedStoreIds)) {
                $query->whereIn('store_id', $allowedStoreIds);
            } else {
                $query->where('id', 0);
            }
        }

        if ($request->filled('store_id')) {
            if ($user->role === 'super_admin' || in_array($request->store_id, $allowedStoreIds)) {
                $query->where('store_id', $request->store_id);
            }
        }

        if ($request->filled('supplier_id')) {
            $query->where('Supplier_id', $request->supplier_id);
        }

        if ($request->filled('customer_name')) {
            $customerName = trim($request->customer_name);
            if ($customerName !== '') {
                $query->where('customer_name', 'like', '%' . $customerName . '%');
            }
        }

        $query->orderBy('order_date', 'desc');
        $orders = $query->paginate(50)->withQueryString();

        if ($user->role === 'admin') {
            $stores = $user->stores;
            $suppliers = Supplier::whereHas('orders', function ($q) use ($allowedStoreIds) {
                $q->whereIn('store_id', $allowedStoreIds);
            })->orderBy('first_name')->get();
        } else {
            $stores = Store::orderBy('name')->get();
            $suppliers = Supplier::orderBy('first_name')->get();
        }

        return view('admin.orders.completed', compact('orders', 'suppliers', 'stores'));
    }

    public function bulkStatusForm()
    {
        return view('admin.orders.bulk-status');
    }

    public function bulkStatusUpdate(Request $request)
    {
        $data = $request->validate([
            'customer_names' => 'required|string',
            'status' => 'required|in:pending,main_time,extra_time,not_shipped,completed',
        ]);

        $rawNames = preg_split('/,/', $data['customer_names']);
        $inputMap = [];
        foreach ($rawNames as $rawName) {
            $name = trim($rawName);
            if ($name === '') {
                continue;
            }
            $lower = strtolower($name);
            if (!isset($inputMap[$lower])) {
                $inputMap[$lower] = $name;
            }
        }

        if (empty($inputMap)) {
            return back()
                ->withErrors(['customer_names' => 'Please provide at least one customer name.'])
                ->withInput();
        }

        $user = auth()->user();
        $query = Order::query();

        if ($user->role === 'admin') {
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();
            if (!empty($allowedStoreIds)) {
                $query->whereIn('store_id', $allowedStoreIds);
            } else {
                $query->where('id', 0);
            }
        }

        $namesLower = array_keys($inputMap);
        $matchQuery = clone $query;
        $matchedLower = $matchQuery
            ->select(DB::raw('LOWER(customer_name) as customer_lower'))
            ->whereIn(DB::raw('LOWER(customer_name)'), $namesLower)
            ->distinct()
            ->pluck('customer_lower')
            ->toArray();

        $updateQuery = clone $query;
        $updatedOrders = 0;
        if (!empty($matchedLower)) {
            $updatedOrders = $updateQuery
                ->whereIn(DB::raw('LOWER(customer_name)'), $matchedLower)
                ->update(['status' => $data['status']]);
        }

        $missingLower = array_values(array_diff($namesLower, $matchedLower));
        $missingNames = array_map(function ($lower) use ($inputMap) {
            return $inputMap[$lower];
        }, $missingLower);

        $updatedNames = array_map(function ($lower) use ($inputMap) {
            return $inputMap[$lower];
        }, $matchedLower);

        return back()->with('bulk_status_summary', [
            'updated_names' => $updatedNames,
            'not_found_names' => $missingNames,
            'updated_orders' => $updatedOrders,
            'status' => $data['status'],
        ]);
    }

    public function swapColorSize(Request $request)
    {
        $user = auth()->user();
        $query = Order::query();

        if ($user->role === 'admin') {
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();
            if (!empty($allowedStoreIds)) {
                $query->whereIn('store_id', $allowedStoreIds);
            } else {
                $query->where('id', 0);
            }
        }

        $updated = 0;
        $query->select('id', 'color', 'size')
            ->orderBy('id')
            ->chunkById(500, function ($orders) use (&$updated) {
                foreach ($orders as $order) {
                    $order->update([
                        'color' => $order->size,
                        'size' => $order->color,
                    ]);
                    $updated++;
                }
            });

        return back()->with('success', "Swapped color and size for {$updated} orders.");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::all();
        $Suppliers = Supplier::all();

        return view('admin.orders.create', compact('stores', 'Suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();

        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'main_days_allocated' => 'required|integer|min:1',
            'extra_days_allocated' => 'required|integer|min:0',
            'image_path' => 'nullable|array',
            'image_path.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'compress_images' => 'nullable|boolean',
            'customer_name' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'country'       => 'nullable|string|max:100',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:1',
            'note'          => 'nullable|string',
        ]);

        $data = $request->except('_token', 'image_path', 'compress_images', 'swap_color_size');
        $data['status'] = 'main_time';

        if ($request->boolean('swap_color_size')) {
            $originalColor = $data['color'] ?? null;
            $data['color'] = $data['size'] ?? null;
            $data['size'] = $originalColor;
        }

        $compress = $request->boolean('compress_images');
        $paths = [];

        if ($request->hasFile('image_path')) {
            $files = $request->file('image_path');
            if (!is_array($files)) {
                $files = [$files];
            }
            $paths = $this->storeUploadedImages($files, $compress);
        }

        if (!empty($paths)) {
            $data['image_path'] = json_encode($paths);
        }

        try {
            Order::create($data);
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order created!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);


        $stores = Store::all();
        $Suppliers = Supplier::all();


        return view('admin.orders.edit', compact('order', 'stores', 'Suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        DB::beginTransaction();

        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'order_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:pending,main_time,extra_time,not_shipped,completed',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'main_days_allocated' => 'required|integer|min:1',
            'extra_days_allocated' => 'required|integer|min:0',
            'image_path' => 'nullable|array',
            'image_path.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'compress_images' => 'nullable|boolean',
            'customer_name' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'country'       => 'nullable|string|max:100',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'note'          => 'nullable|string',
        ]);

        $data = $request->except('_token', '_method', 'image_path', 'compress_images', 'swap_color_size');
        $compress = $request->boolean('compress_images');

        if ($request->boolean('swap_color_size')) {
            $originalColor = $data['color'] ?? null;
            $data['color'] = $data['size'] ?? null;
            $data['size'] = $originalColor;
        }

        $paths = [];
        if ($request->hasFile('image_path')) {
            $files = $request->file('image_path');
            if (!is_array($files)) {
                $files = [$files];
            }
            $paths = $this->storeUploadedImages($files, $compress);
        }

        if (!empty($paths)) {
            $this->deleteOrderImages($order);
            $data['image_path'] = json_encode($paths);
        }
        try {
            $order->update($data);
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order updated!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        DB::beginTransaction();

        try {
            $order->delete();
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function storeUploadedImages(array $files, bool $compress)
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->storeSingleImage($file, $compress);
        }
        return $paths;
    }

    private function storeSingleImage($file, bool $compress)
    {
        if (!$compress) {
            return $file->store('orders', 'public');
        }

        if (!function_exists('imagecreatefromjpeg') || !function_exists('imagecreatefrompng')) {
            return $file->store('orders', 'public');
        }

        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = uniqid('order_', true) . '.' . $extension;
        $path = 'orders/' . $filename;

        $contents = null;
        if (in_array($mime, ['image/jpeg', 'image/jpg'], true)) {
            $image = @\imagecreatefromjpeg($file->getRealPath());
            if ($image) {
                ob_start();
                \imagejpeg($image, null, 75);
                $contents = ob_get_clean();
                \imagedestroy($image);
            }
        } elseif ($mime === 'image/png') {
            $image = @\imagecreatefrompng($file->getRealPath());
            if ($image) {
                ob_start();
                \imagepng($image, null, 6);
                $contents = ob_get_clean();
                \imagedestroy($image);
            }
        }

        if ($contents === null) {
            return $file->store('orders', 'public');
        }

        Storage::disk('public')->put($path, $contents);
        return $path;
    }

    private function deleteOrderImages(Order $order)
    {
        $paths = [];
        if (!empty($order->image_path)) {
            $decoded = json_decode($order->image_path, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $paths = array_merge($paths, $decoded);
            } else {
                $paths[] = $order->image_path;
            }
        }

        $paths = array_unique(array_filter($paths));
        if (!empty($paths)) {
            Storage::disk('public')->delete($paths);
        }
    }
}
