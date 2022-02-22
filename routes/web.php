<?php

use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Goutte\Client;
use Illuminate\Support\Facades\Route;
use Service\AltinYildiz\AltinYildizClient;
use Service\AltinYildiz\Requests\Products;

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

Route::get('/', function () {

    $client = new \Service\AltinYildiz\Requests\Categories();
    $data = $client->getSubCategories('Desenli Ceket ', 'desenli-ceket-c-2743');
//    $data = $client->getShoesCategories();
    return $data;
});
