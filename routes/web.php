<?php

use App\Http\Controllers\Admin\Auth\UserController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    $client = new \Service\Mavi\MaviClient();

    $products = \Domains\Products\Models\Product::whereServiceType(3)
        ->where('in_stock', 1)
        ->with('price')
        ->select('id', 'internal_code')
        ->get()
        ->keyBy('internal_code');
    dd($products);
    dd($client->getPricesFromAPI("/erkek/c/2/results?q=:relevance:categoryValue:Sweatshirt&page=0"));
//    return ($client->getFromAPI("/erkek/c/2/results?q=:relevance:categoryValue:Sweatshirt&page=1000"));

//    return redirect()->route('admin.index');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', fn() => view('admin.auth.login'))->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [ProductsController::class, 'index'])->name('admin.index');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/changePassword', [NewPasswordController::class, 'changePassword'])->name('changePassword');
    Route::get('/products', [ProductsController::class, 'products'])->name('admin.products');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/product/{id}', [ProductsController::class, 'productSingle'])->name('admin.product');
    Route::post('/product/id-{id}/serv-{sid}/check', [ProductsController::class, 'checkPrice'])->name('check-price');
    Route::post('/products/export', [ProductsController::class, 'export'])->name('product.export');
});
