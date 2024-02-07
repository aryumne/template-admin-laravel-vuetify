<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\BlogTypeController;
use App\Http\Controllers\api\ProductTypeController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\SalesController;
use App\Http\Controllers\frontend\FeBlogController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\PurchaseController;

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
        Route::get('me', [AuthenticationController::class, 'me'])->middleware('auth:sanctum');
        Route::post('login', [AuthenticationController::class, 'signIn']);
        Route::post('logout', [AuthenticationController::class, 'signOut'])->middleware('auth:sanctum');
    });
    Route::group(['prefix' => 'fe'], function () {
        Route::get('detail/{slug}', [FeBlogController::class, 'show']);
        Route::get('destinations', [FeBlogController::class, 'getDestinations']);
        Route::get('festivals', [FeBlogController::class, 'getFestivals']);
        Route::get('inspirations', [FeBlogController::class, 'getInspiration']);
        Route::get('trips', [FeBlogController::class, 'getTrips']);
        Route::get('culinaries', [FeBlogController::class, 'getCulinaries']);
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        // Blog
        Route::resource('blog-types', BlogTypeController::class)->only('index');
        Route::resource('blogs', BlogController::class);
        Route::get('blogs-group', [BlogController::class, 'groupByTypeKey']);

        // POS Cashier
        Route::get('product-types', [ProductTypeController::class, 'index']);
        Route::resource('products', ProductController::class);
        Route::post('products-download', [ProductController::class, 'download']);
        Route::get('products-search', [ProductController::class, 'search']);
        Route::get('product-barcode-search/{barcode}', [ProductController::class, 'searchByBarcode']);
        Route::resource('transactions', TransactionController::class)->only(['index', 'store', 'show']);
        Route::resource('sales', SalesController::class)->only('index');
        Route::post('sales-download', [SalesController::class, 'download']);
        Route::resource('purchases', PurchaseController::class)->only(['index', 'store']);
        Route::post('purchases-download', [PurchaseController::class, 'download']);
    });
});
