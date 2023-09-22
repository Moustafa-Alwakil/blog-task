<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PostResourceController;
use App\Http\Controllers\Api\CommentResourceController;

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

// Start Auth Routes

Route::controller(AuthController::class)->group(function () {

    Route::middleware('guest:sanctum')->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
    });

    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

// End Auth Routes


Route::controller(PostController::class)->name('posts.')->group(function () {
    Route::get('/', 'all')->name('all');
    Route::get('post/{post:slug}', 'single');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostResourceController::class);
    Route::apiResource('comments', CommentResourceController::class)->except('index', 'show');

    Route::put('profile', ProfileController::class);
});
