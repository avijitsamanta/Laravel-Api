<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('buyers','Buyer\BuyerController',['only'=>['index','show']]);
Route::resource('buyers.transactions','Buyer\BuyerTransactionController',['only'=>['index','show']]);
Route::resource('buyers.products','Buyer\BuyerProductController',['only'=>['index','show']]);
Route::resource('buyers.sellers','Buyer\BuyerSellerController',['only'=>['index','show']]);
Route::resource('buyers.categories','Buyer\BuyerCategoryController',['only'=>['index','show']]);





Route::resource('sellers','Seller\SellerController',['except'=>['create','edit']]);
Route::resource('sellers.transactions','Seller\SellerTransactionController',['except'=>['create','edit']]);
Route::resource('sellers.categories','Seller\SellerCategoryController',['except'=>['create','edit']]);
Route::resource('sellers.buyers','Seller\SellerBuyerController',['except'=>['create','edit']]);
Route::resource('sellers.products','Seller\SellerProductController');





Route::resource('categories','Category\CategoryController');
Route::resource('categories.products','Category\CategoryProductController');
Route::resource('categories.sellers','Category\CategorySellerController');
Route::resource('categories.buyers','Category\CategoryBuyerController');
Route::resource('categories.transactions','Category\CategoryTransactionController');





Route::resource('products','Product\ProductController',['only'=>['index','show']]);
Route::resource('products.buyers','Product\ProductBuyerController',['only'=>['index','show']]);
Route::resource('products.transactions','Product\ProductTransactionController',['only'=>['index','show']]);
Route::resource('products.categories','Product\ProductCategoryController');
Route::resource('products.buyers.transactions','Product\ProductBuyerTransactionController');







Route::resource('transactions','Transaction\TransactionController',['only'=>['index','show']]);
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only'=>['index','show']]);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only'=>['index','show']]);




Route::resource('users','User\UserController',['except'=>['create','edit']]);



