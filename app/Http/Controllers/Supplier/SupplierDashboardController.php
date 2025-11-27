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

        // $orders = $supplierProfile->orders()
        //                     ->with('store')
        //                     ->orderBy('created_at', 'desc')
        //                     ->get();


        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('created_at', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
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
