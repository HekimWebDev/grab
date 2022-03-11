<?php


use App\Http\Controllers\Admin\Auth\UserController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\IntlLocalizedDecimalParser;

Route::get('/', function () {

});
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', fn() => view('admin.auth.login'))->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [ProductsController::class, 'index'])->name('admin.index');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/changePassword', [NewPasswordController::class, 'changePassword'])->name('changePassword');

    Route::get('/altin-yildiz', [ProductsController::class, 'altinYildiz'])->name('admin.a-y');
//    Route::get('/altin-yildiz', [ProductsController::class, 'altinYildiz'])->name('admin.a-y');
    Route::get('/altin-yildiz/dashboard', fn() => view('admin.altinyildiz.dashboard'))->name('admin.a-y-dashboard');
    Route::get('/altin-yildiz/{id}', [ProductsController::class, 'altinYildizSingle'])->name('admin.a-y-single');
    Route::post('/altin-yildiz/{id}/check', [ProductsController::class, 'altinYildizCheck'])->name('admin.a-y-check');

});

Route::get('casts', function () {
    return 'Casts';
});
