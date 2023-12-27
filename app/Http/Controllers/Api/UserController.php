<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\UserResource;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::latest()->get();
        return $this->sendResponse(UserResource::collection($users), 'User fetched successfully', 200);
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User Not Found', 'User Not Found!', 404);
        }
        return $this->sendResponse(new UserResource($user), 'User Fetched', 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ], [
            'email.required' => "Email field is missing",
            'email.string' => 'Email field must be string',
            'password.required' => 'Password is required',
            'password.max' => 'Password must be more than 5 characters'
        ]);

        $user = User::where('email', $request->email);

        if (!$user) {
            return $this->sendError("User Not Found", "User Not Found", 404);
        }

        if ($user && Hash::check($request->password, $user->password)) {
            $userToken = $user->createToken($request->device_name);
            return $this->sendResponse($userToken->plainTextToken, 'Login Is Successful');
        }
        return false;
    }
}
