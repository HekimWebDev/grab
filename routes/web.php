<?php

use Illuminate\Support\Facades\Route;
use Service\AltinYildiz\AltinYildizClient;

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

    $client = new AltinYildizClient();
    $content = $client->getResponse()->getContent('.section-title');

    return response($content);
//    $res = $client->getContent('.section-title');

//    return view('welcome', compact('res'));
});
