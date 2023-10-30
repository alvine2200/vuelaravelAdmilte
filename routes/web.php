<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
