<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UsersController;

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
    Route::get('members', 'index');
    Route::get('member/{username}', 'show');
    Route::put('members', 'update');
}); 
