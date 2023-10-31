<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return UserResource::collection($users);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return new UserResource($user);
    }
}
