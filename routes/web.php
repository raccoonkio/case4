<?php

use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookstoreController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookstoreController::class, 'index'])->name('store.index');
Route::get('/books/{book}', [BookstoreController::class, 'show'])->name('store.show');
Route::get('/account', [BookstoreController::class, 'account'])->name('store.account')->middleware('role:user');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store');

Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('books', AdminBookController::class)->except(['show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/remind', [AdminOrderController::class, 'remind'])->name('orders.remind');
});
