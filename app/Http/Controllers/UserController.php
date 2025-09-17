<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::orderBy('nama')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%")
                             ->orWhere('role', 'like', "%{$search}%"); // 🔥 Tambahkan filter role
            })
            ->paginate(10);

        if ($search) {
            $users->appends(['search' => $search]);
        }

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('store', 'success');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return redirect()->route('user.index')->with('update', 'success');
    }

    public function destroy(Request $request, User $user)
    {
        // Cegah menghapus user yang sedang login
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus pengguna karena anda sebagai admin.');
        }

        $user->delete();

        return back()->with('destroy', 'success');
    }
}
