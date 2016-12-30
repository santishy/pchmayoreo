<?php

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
    return view('welcome');
});
Route::get('pagos/updateAndStore','PaymentsController@updateAndStore');
Route::get('pagos/{shopping_cart_id}','PaymentsController@create');// poner primero 
//que una resource 

Route::resource('pagos','PaymentsController',['online'=>['store']]);

Route::resource('in_shopping_carts','InShoppingCartsController',['only'=>['store','update','destroy']]);
Route::get('articles/image/{fileName}','ArticlesController@images');
Auth::routes();
Route::get('/home', 'HomeController@index');
Route::resource('remisiones','RemisionesController');
Route::resource('compras','ShoppingCartsController',['only'=>['show']]);
Route::resource('orders','OrdersController');

Route::get('configuraciones/image/{fileName}','ImagesPage@images');
