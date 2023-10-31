<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\Admin\UserResource;
use App\Http\Controllers\Api\BaseController;

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
}
