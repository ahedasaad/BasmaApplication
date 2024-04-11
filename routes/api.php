<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

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

/*
|--------------------------------------------------------------------------
| Users Management
|--------------------------------------------------------------------------
*/

Route::prefix('user_register')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/', 'register');
        Route::post('/verify', 'verifyCode');
        Route::post('/resend', 'resendCode');
        Route::post('/login', 'login');
    });

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

/*
|--------------------------------------------------------------------------
| Posts Management
|--------------------------------------------------------------------------
*/
Route::prefix('posts')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/post/filter', 'filter');
        Route::post('/accept/{id}', 'acceptPost');
        Route::post('/unaccept/{id}', 'unacceptPost');
        Route::get('/user/get_user_posts', 'getUserPosts');
    });