<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AccountController::class)->group(function () {
    Route::post('account/login', 'login');
    Route::post('account/register', 'register');
});
Route::controller(UsersController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('users/{username}', 'show');
    Route::put('users', 'update');
});
Route::controller(LikeController::class)->group(function () {
    Route::get('likes', 'index');
    Route::post('likes/{username}', 'store');
});

Route::controller(MessageController::class)->group(function () {
    Route::get('messages', 'index');
    Route::post('messages/{username}', 'show');
});
