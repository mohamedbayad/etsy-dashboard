<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. --- BASE QUERY & PERMISSIONS (For Orders) ---
        $query = Order::with(['store', 'supplier']);
        $allowedStoreIds = [];

        if ($user->role === 'admin') {
            // Get assigned store IDs
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();

            if (!empty($allowedStoreIds)) {
                $query->whereIn('store_id', $allowedStoreIds);
            } else {
                $query->where('id', 0); // No access
            }
        }

        // --- UPDATED: Suppliers Stats Logic ---
        $supplierBaseQuery = Supplier::whereHas('user');
        if ($user->role === 'admin') {
            // Only count suppliers linked to the admin's assigned stores and active users
            $totalSuppliers = $supplierBaseQuery->whereHas('orders', function($q) use ($allowedStoreIds) {
                $q->whereIn('store_id', $allowedStoreIds);
            })->count();
        } else {
            // Super Admin sees total count of suppliers with user accounts
            $totalSuppliers = $supplierBaseQuery->count();
        }

        // 2. --- APPLY SEARCH FILTERS (For the Orders Table) ---

        // Filter by Store
        if ($request->filled('store_id')) {
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

        $query->where('status', '!=', 'completed');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. --- CALCULATE STATS (KPIs) USING FILTERED QUERY ---
        $statsQuery = clone $query;
        $totalOrders = $statsQuery->count();
        $notShippedOrders = (clone $statsQuery)->where('status', 'not_shipped')->count();
        $extraTimeOrders = (clone $statsQuery)->where('status', 'extra_time')->count();
        $completedQuery = Order::query();
        if ($user->role === 'admin') {
            if (!empty($allowedStoreIds)) {
                $completedQuery->whereIn('store_id', $allowedStoreIds);
            } else {
                $completedQuery->where('id', 0);
            }
        }
        $completedOrders = $completedQuery->where('status', 'completed')->count();

        // 4. --- SORTING LOGIC ---
        if ($request->filled('sort_retard')) {
            if ($request->sort_retard == 'most_retarded') {
                $query->orderBy('days_spent_extra', 'desc');
            } elseif ($request->sort_retard == 'least_retarded') {
                $query->orderBy('days_spent_extra', 'asc');
            }
        } else {
            // Default Priority Sorting
            $query->orderBy('days_spent_extra', 'desc')
                  ->orderBy('days_spent_main', 'desc')
                  ->orderBy('order_date', 'asc');
        }

        // 5. --- GET DATA ---
        $orders = $query->paginate(50)->withQueryString();

        // 6. --- PREPARE DROPDOWNS ---
        if ($user->role === 'admin') {
            $stores = $user->stores;

            // Filter Suppliers dropdown to only show relevant ones
            $suppliers = Supplier::whereHas('orders', function($q) use ($allowedStoreIds) {
                $q->whereIn('store_id', $allowedStoreIds);
            })->orderBy('first_name')->get();

        } else {
            $stores = Store::orderBy('name')->get();
            $suppliers = Supplier::orderBy('first_name')->get();
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'notShippedOrders',
            'extraTimeOrders',
            'completedOrders',
            'totalSuppliers',
            'orders',
            'suppliers',
            'stores'
        ));
    }
}
