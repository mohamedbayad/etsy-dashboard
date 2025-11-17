<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
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
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,supplier',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'name' => 'nullable|required_if:role,admin|string|max:255',
            'first_name' => 'nullable|required_if:role,supplier|string|max:255',
            'last_name' => 'nullable|required_if:role,supplier|string|max:255',
            'specialty' => 'nullable|required_if:role,supplier|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            $role = $request->role;

            $user = User::create([
                'name' => ($role == 'admin') ? $request->name : $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);

            if ($role == 'supplier') {
                $user->SupplierProfile()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialty' => $request->specialty,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('SupplierProfile')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
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

            'name' => 'nullable|required_if:role,admin|string|max:255',
            'first_name' => 'nullable|required_if:role,supplier|string|max:255',
            'last_name' => 'nullable|required_if:role,supplier|string|max:255',
            'specialty' => 'nullable|required_if:role,supplier|string|max:255',
        ]);

        DB::transaction(function () use ($request, $user) {

            $userData = [
                'name' => ($user->role == 'admin') ? $request->name : $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($user->role == 'supplier' && $user->SupplierProfile) {
                $user->SupplierProfile->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialty' => $request->specialty,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
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
