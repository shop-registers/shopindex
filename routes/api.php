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
	Route::get('goodstype', 'GoodsTypeController@getgoodstype'); //
	Route::get('goodslist/{id?}', 'GoodsTypeController@getgoods');//
	Route::get('getgoods1/{id?}', 'GoodsTypeController@getgoods1');//分类商品1
	Route::get('getgoods2/{id?}', 'GoodsTypeController@getgoods2');//分类商品2
	Route::get('getgoods3/{id?}', 'GoodsTypeController@getgoods3');//分类商品3
	Route::get('getgoods4/{id?}', 'GoodsTypeController@getgoods4');//分类商品4
	Route::get('brand', 'GoodsTypeController@getbrand');//
	Route::get('goodtype_show/{id?}','GoodinfoController@goodtype_show');//商品分类页面展示
});
Route::get('/good_onceinfo','GoodinfoController@good_showinfo');//商品的详情页展示
Route::get('/good_comment','GoodinfoController@good_comment');//商品评论内容
Route::get('/add_collect','GoodinfoController@add_collect');//添加收藏
Route::get('/add_shopcart','GoodinfoController@add_shopcart');//添加到购物车
Route::get('/add_order','GoodinfoController@add_order');//确认下单
Route::get('/goodattr_change','GoodinfoController@goodattr_change');//ajax改变商品价格


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//购物车列表
Route::any("/car_list",'Shopping_CartController@car_list');


//收藏添加
Route::post("/my_collections",'CollectionsController@my_collections');
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//订单接口
Route::any('/FullOrder','FullOrderController@FullOrder');
Route::any('/CommentedOrder','FullOrderController@CommentedOrder');
Route::any('/ReceiveddOrder','FullOrderController@ReceiveddOrder');
Route::any('/PaidOrder','FullOrderController@PaidOrder');


//地址接口
Route::any('/Myaddress','AddressController@Myaddress');

Route::any('/AddAddress','AddressController@AddAddress');
Route::any('/DelAddress','AddressController@DelAddress');
Route::any('/UpdateAddress','AddressController@UpdateAddress');
