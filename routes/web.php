<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('{any?}', function () {
    return view('application');
})->where('any', '.*');

Route::post('/login', [AuthenticationWebController::class, 'signIn'])->middleware(['redirect.if.authenticated']);
Route::post('/logout', [AuthenticationWebController::class, 'signOut'])->middleware(['auth']);
