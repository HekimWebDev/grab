<?php

use App\Http\Controllers\Admin\Auth\UserController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\IntlLocalizedDecimalParser;

Route::get('/', function () {
    $client = new \Domains\ServiceManagers\AltinYildiz\AltinYildizManager();
    $client->createProducts();

//    \Domains\Prices\Models\Price::where('original_price', '>', 0)->delete();

//    $client = new \Domains\ServiceManagers\AltinYildiz\AltinYildizManager();
//    $client->updatePrice();

//    $money = new \App\Casts\Money();
//    dd($money->set('', 's', '1999,15 TL', [])['s']);

});
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', fn() => view('admin.auth.login'))->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [ProductsController::class, 'index'])->name('admin.index');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/changePassword', [NewPasswordController::class, 'changePassword'])->name('changePassword');

    Route::get('/products', [ProductsController::class, 'altinYildiz'])->name('admin.a-y');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.a-y-dashboard');
    Route::get('/product/{id}', [ProductsController::class, 'altinYildizSingle'])->name('admin.a-y-single');
    Route::post('/product/id-{id}/serv-{sid}/check', [ProductsController::class, 'checkPrice'])->name('check-price');

});

Route::get('casts', function () {
    return 'Casts';
});
