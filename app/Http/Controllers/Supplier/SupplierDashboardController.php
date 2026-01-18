<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Supplier;
use App\Models\Order;
use App\Models\Store;



class SupplierDashboardController extends Controller
{
    public function index( Request $request )
    {
        $user = Auth::user();

        $supplierProfile = $user->supplierProfile;

        if (!$supplierProfile) {
            Auth::logout();
            return redirect('/login')->with('error', 'Profile not configured. Contact admin.');
        }

        $query = $supplierProfile->orders()
                    ->with(['store', 'supplier']);

        if ($request->filled('customer_name')) {
            $customerName = trim($request->customer_name);
            if ($customerName !== '') {
                $query->where('customer_name', 'like', '%' . $customerName . '%');
            }
        }

        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('created_at', $request->sort);
        } else {
            $query->orderBy('days_spent_extra', 'desc');
            $query->orderBy('days_spent_main', 'desc');
            $query->orderBy('order_date', 'asc');
        }

        $orders = $query->get();


        return view('supplier.dashboard',
        compact(
            'supplierProfile',
            'orders',
            )
        );
    }
}
