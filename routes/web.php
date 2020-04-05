<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/proxy', function () {
    return response('Hello, world!')->withHeaders(['Content-Type' => 'application/liquid']);
})->middleware('auth.proxy');

Route::get('/', function () {
    $shop = Auth::user();
    $request = $shop->api()->rest('GET', '/admin/api/products.json');
    $products = collect($request->body->products);

    // Upsert all products based upon product id
    dd($products);

    Log::info('Imported products for store: '.json_encode($shop));
    
        
    return view('welcome');
})->middleware(['auth.shopify'])->name('home');


Route::get('shopify', 'ShopifyController@index')->middleware(['auth.shopify']);
