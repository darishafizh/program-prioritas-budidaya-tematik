<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            'role' => ['required', 'in:admin,verifikator'],
        ], [
            'name.regex' => 'Username hanya boleh mengandung huruf abjad dan angka independensi kapital (tanpa spasi/karakter spesial).'
        ]);

        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id, 'regex:/^[a-zA-Z0-9]+$/'],
            'role' => ['required', 'in:admin,verifikator'],
        ], [
            'name.regex' => 'Username hanya boleh mengandung huruf abjad dan angka (tanpa spasi/karakter spesial).'
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
