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
    public function index( Request $request )
    {
        $query = Order::with(['store', 'supplier']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {

            $query->orderBy('created_at', $request->sort);
        } else {

            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->get();

        $suppliers = Supplier::orderBy('first_name')->get();

        return view('admin.orders.index', compact('orders', 'suppliers'));
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
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        } catch ( Exception $e ) {
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
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:main_time,extra_time,completed',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'main_days_allocated' => 'required|integer|min:1',
            'extra_days_allocated' => 'required|integer|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
