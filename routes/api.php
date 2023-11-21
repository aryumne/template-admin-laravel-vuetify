<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\BlogTypeController;
use App\Http\Controllers\api\AuthenticationController;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthenticationController::class, 'signIn']);
        Route::get('me', [AuthenticationController::class, 'me'])->middleware('auth:sanctum');
        Route::post('logout', [AuthenticationController::class, 'signOut'])->middleware('auth:sanctum');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('blog-types', BlogTypeController::class)->only('index');
        Route::resource('blogs', BlogController::class)->except(['update', 'destroy']);
    });
});
