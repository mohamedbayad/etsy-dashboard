<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource (PAGE DYAL L-CARDS)
     */
    public function index()
    {
        $suppliers = Supplier::with('user')
            ->withCount('orders')
            ->whereHas('user')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource (PAGE DYAL ADD)
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'supplier',
            ]);

            $user->SupplierProfile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'specialty' => $request->specialty,
            ]);
        });

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier créé avec succès.');
    }

    /**
     * Display the specified resource (PAGE DYAL TABLE)
     */
    public function show(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $ordersQuery = $supplier->orders()->with(['store', 'supplier']);

        if ($request->filled('customer_name')) {
            $customerName = trim($request->customer_name);
            if ($customerName !== '') {
                $ordersQuery->where('customer_name', 'like', '%' . $customerName . '%');
            }
        }

        $orders = $ordersQuery->get();

        return view('admin.suppliers.show', compact('supplier', 'orders'));
    }

    /**
     * Show the form for editing the specified resource (PAGE DYAL EDIT)
     */
    public function edit($id)
    {
        $Supplier = Supplier::with('user')->findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $Supplier = Supplier::findOrFail($id);
        $user = $Supplier->user;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request, $Supplier, $user) {
            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            $Supplier->update($request->only('first_name', 'last_name', 'specialty'));
        });

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Supplier = Supplier::findOrFail($id);

        $Supplier->user->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier (et son compte) supprimé.');
    }
}
