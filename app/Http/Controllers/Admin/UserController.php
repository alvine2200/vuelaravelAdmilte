<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(5);
        return $users;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }

    public function edit($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);
        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return $user;
    }

    public function delete(Request $request)
    {
        User::find($request->userId)->delete();
        return response()->noContent();
    }

    public function changeRole(User $user, Request $request)
    {
        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'like', "%{$request['query']}%")->paginate(5);
        return response()->json($users);
    }

    public function bulkDelete(Request $request)
    {
        User::whereIn('id', $request['ids'])->delete();
        return response()->json([
            'message' => 'User Deleted Successfully'
        ]);
    }
}
