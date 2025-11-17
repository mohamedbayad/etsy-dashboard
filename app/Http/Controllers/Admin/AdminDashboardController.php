<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index( Request $request )
    {
        // we count the total orders, extra time orders and total Suppliers
        $totalOrders = Order::count();
        $extraTimeOrders = Order::where('status', 'extra_time')->count();
        $totalSuppliers = Supplier::count();

        // last 10 orders
        $recentOrders = Order::with(['store', 'supplier']) 
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();

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

        return view('admin.dashboard', compact(
            'totalOrders',
            'extraTimeOrders',
            'totalSuppliers',
            'orders',
            'suppliers',
        ));
    }
}
