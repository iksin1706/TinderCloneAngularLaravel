<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;

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
    Route::post('users/add-photo', 'addPhoto');
    Route::put('users', 'update');
    Route::delete('users/delete-photo/{id}', 'deletePhoto');
    Route::put('users/set-main-photo/{id}', 'setMainPhoto');
});
Route::controller(LikeController::class)->group(function () {
    Route::get('likes', 'index');
    Route::post('likes/{username}', 'store');
});

Route::controller(MessageController::class)->group(function () {
    Route::get('messages', 'index');
    Route::get('messages/{username}', 'thread');
    Route::post('messages', 'store');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('admin/users-with-roles', 'usersWithRoles');
    Route::put('admin/edit-role/{username}', 'editRole');
});

Route::controller(BanController::class)->group(function () {
    Route::get('admin/blockages', 'index');
    Route::post('user/{username}/ban', 'ban');
})
;Route::controller(ReportController::class)->group(function () {
    Route::get('admin/reports', 'index');
    Route::post('user/{username}/report', 'report');
});




