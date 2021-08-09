<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// generate user reports
Route::get('users/reports/all-users', [UserController::class, 'allUsersReport']);

Route::get('users/reports/all-users-with-roles', [UserController::class, 'allUsersWithRoles']);
