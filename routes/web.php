<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('home')->get('/', [App\Http\Controllers\ProductController::class, 'getAll']);

Route::name('products')->get('products', [App\Http\Controllers\ProductController::class, 'getAll']);
Route::post('products', [App\Http\Controllers\ProductController::class, 'createAmount']);

Route::get('cart', [App\Http\Controllers\PurchaseController::class, 'get']);
Route::post('cart', [App\Http\Controllers\PurchaseController::class, 'create']);
