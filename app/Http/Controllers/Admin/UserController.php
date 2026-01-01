<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('SupplierProfile')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (auth()->user()->role !== 'super_admin') {
            return redirect()->route('admin.users.index')->with('error', 'You are not authorized to create users.');
        }

        $stores = Store::orderBy('name')->get();
        return view('admin.users.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'role' => 'required|in:admin,supplier,super_admin',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'name' => 'nullable|required_if:role,admin|string|max:255',
            'first_name' => 'nullable|required_if:role,supplier|string|max:255',
            'last_name' => 'nullable|required_if:role,supplier|string|max:255',
            'specialty' => 'nullable|required_if:role,supplier|string|max:255',
        ]);

        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'admin' && $request->has('stores')) {
            $user->stores()->attach($request->stores);
        }

        return redirect()->route('admin.users.index')->with('success', 'Created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('SupplierProfile')->findOrFail($id);
        $stores = Store::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,supplier,super_admin',
            'name' => 'nullable|required_if:role,admin|string|max:255',
            'first_name' => 'nullable|required_if:role,supplier|string|max:255',
            'last_name' => 'nullable|required_if:role,supplier|string|max:255',
            'specialty' => 'nullable|required_if:role,supplier|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'admin') {
            $user->stores()->sync($request->input('stores', []));
        } else {
            $user->stores()->detach();
        }


        return redirect()->route('admin.users.index')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
