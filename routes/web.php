<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductsController;
use Service\AltinYildiz\Requests\Products;



Route::get('/', function () {

    $products = new Products();
    $products = $products->getProducts();

    foreach ($products as $product) {
        dd($product);
    }
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [ProductsController::class, 'index'])->name('admin.index');

    Route::get('/altin-yildiz', [ProductsController::class, 'altinYildiz'])->name('admin.a-y');
//    Route::get('/altin-yildiz-ajax', [ProductsController::class, 'getProductsAjax'])->name('admin.a-y-ajax');
    Route::get('/altin-yildiz/{id}', [ProductsController::class, 'altinYildizSingle'])->name('admin.a-y-single');

});
