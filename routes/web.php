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
Route::post('products', [App\Http\Controllers\ProductController::class, 'create']);
Route::get('products/{name}', [App\Http\Controllers\ProductController::class, 'getByName']);

Route::name('products')->post('products/{name}/change_name', [App\Http\Controllers\ProductController::class, 'changeName']);
Route::name('products')->post('products/{name}/change_price', [App\Http\Controllers\ProductController::class, 'changePrice']);
Route::name('products')->post('products/{name}/change_vat', [App\Http\Controllers\ProductController::class, 'changeVat']);
Route::name('products')->post('products/{name}/change_amount', [App\Http\Controllers\ProductController::class, 'changeAmount']);
Route::name('products')->post('products/{name}/change_description', [App\Http\Controllers\ProductController::class, 'changeDescription']);

Route::get('cart', [App\Http\Controllers\PurchaseController::class, 'get']);
Route::post('cart', [App\Http\Controllers\PurchaseController::class, 'create']);
Route::post('cart/buy', [App\Http\Controllers\PurchaseController::class, 'buy']);
Route::post('cart/remove', [App\Http\Controllers\PurchaseController::class, 'remove']);
Route::post('cart/add', [App\Http\Controllers\PurchaseController::class, 'addToCart']);
