<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Public routes
Route::get('/', [NavigationController::class, 'index'])->name('shop');
Route::apiResource('cart', CartController::class)->except(['destroy', 'show', 'update']);
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::put('cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('categories', CategoryController::class)->except(['show']);
    Route::apiResource('products', ProductController::class)->except(['show']);
    Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
});

