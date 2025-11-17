<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $supplierProfile = $user->supplierProfile;

        if (!$supplierProfile) {
            Auth::logout();
            return redirect('/login')->with('error', 'Profile not configured. Contact admin.');
        }

        $orders = $supplierProfile->orders()
                            ->with('store')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('supplier.dashboard', compact('supplierProfile', 'orders')); // <-- BADAL HADI
    }
}
