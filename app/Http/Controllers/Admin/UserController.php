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
        $users = User::latest()->get();
        return $users;
    }

    public function store(Request $request)
    {
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
        $user->update([
            'name' => $request('name'),
            'email' => $request('email'),
            'password' => $request('password') ? Hash::make($request('password')) : null,
        ]);

        return $user;
    }
}
