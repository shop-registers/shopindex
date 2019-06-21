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
Route::group(['prefix'=>'/'],function(){
	Route::get('goodstype', 'GoodsTypeController@getgoodstype');
	Route::get('goodslist/{id?}', 'GoodsTypeController@getgoods');
	Route::get('getgoods1/{id?}', 'GoodsTypeController@getgoods1');
	Route::get('getgoods2/{id?}', 'GoodsTypeController@getgoods2');
	Route::get('getgoods3/{id?}', 'GoodsTypeController@getgoods3');
	Route::get('getgoods4/{id?}', 'GoodsTypeController@getgoods4');
	Route::get('brand', 'GoodsTypeController@getbrand');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
