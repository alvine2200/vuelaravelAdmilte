<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/api/users', [UserController::class, 'index']);
Route::post('/api/users', [UserController::class, 'store']);
Route::put('/api/users/{user}', [UserController::class, 'update']);
Route::patch('/api/users/{user}/change_role', [UserController::class, 'changeRole']);
Route::post('/api/users/{user}', [UserController::class, 'delete']);
Route::get('/api/users/search', [UserController::class, 'search']);
Route::delete('/api/users', [UserController::class, 'bulkDelete']);

Route::get('/api/appointments', [AppointmentController::class, 'index']);


Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
