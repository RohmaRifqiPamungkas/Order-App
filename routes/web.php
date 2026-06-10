<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', fn () => redirect('/admin/orders'));

Route::get('/admin/orders', [OrderController::class, 'index'])->name('orders.index');
