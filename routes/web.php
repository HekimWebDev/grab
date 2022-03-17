<?php

use App\Http\Controllers\Admin\Auth\UserController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', fn() => view('admin.auth.login'))->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [ProductsController::class, 'index'])->name('admin.index');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/changePassword', [NewPasswordController::class, 'changePassword'])->name('changePassword');
    Route::get('/products', [ProductsController::class, 'altinYildiz'])->name('admin.products');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/product/{id}', [ProductsController::class, 'altinYildizSingle'])->name('admin.product');
    Route::post('/product/id-{id}/serv-{sid}/check', [ProductsController::class, 'checkPrice'])->name('check-price');
    Route::post('/product/{id}/export', [ProductsController::class, 'export'])->name('product.export');
});
