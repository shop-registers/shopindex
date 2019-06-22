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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/good_onceinfo','GoodinfoController@good_showinfo');//商品的详情页展示
Route::get('/good_comment','GoodinfoController@good_comment');//商品评论内容
Route::get('/add_collect','GoodinfoController@add_collect');//添加收藏
Route::post('/add_shopcart','GoodinfoController@add_shopcart');//添加到购物车
Route::post('/add_order','GoodinfoController@add_order');//确认下单
Route::get('/goodattr_change','GoodinfoController@goodattr_change');//ajax改变商品价格
Route::get('/goodtype_show','GoodinfoController@goodtype_show');//商品分类页面展示
Route::get('/payorder','GoodinfoController@payorder');//支付订单页面展示数据
Route::get('/good_pay','GoodinfoController@good_pay');//支付订单