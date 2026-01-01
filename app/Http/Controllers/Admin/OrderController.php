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

        // 3. --- SORTING ---
        // Default sorting logic:
        // 1. Orders in Extra Time (High Delay) first
        // 2. Orders in Main Time
        // 3. Oldest Orders
        $query->orderBy('days_spent_extra', 'desc')
            ->orderBy('days_spent_main', 'desc')
            ->orderBy('order_date', 'asc');

        // 4. --- GET DATA ---
        $orders = $query->get();

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
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000',
            'customer_name' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'country'       => 'nullable|string|max:100',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:1',
            'note'          => 'nullable|string',
        ]);

        $data = $request->except('_token', 'image_path');
        $data['status'] = 'main_time';

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('orders', 'public');
            $data['image_path'] = $path;
        }

        try {
            Order::create($data);
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order created!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e);
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
            'status' => 'required|in:main_time,extra_time,completed',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'main_days_allocated' => 'required|integer|min:1',
            'extra_days_allocated' => 'required|integer|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_name' => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'country'       => 'nullable|string|max:100',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'note'          => 'nullable|string',
        ]);

        $data = $request->except('_token', '_method', 'image_path');

        if ($request->hasFile('image_path')) {

            if ($order->image_path) {
                Storage::disk('public')->delete($order->image_path);
            }

            $path = $request->file('image_path')->store('orders', 'public');
            $data['image_path'] = $path;
        }
        try {
            $order->update($data);
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order updated!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e);
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
            return back()->with('error', $e);
        }
    }
}
