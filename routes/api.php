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

//注册
Route::post('/register','Shop_apiController@register');

//激活账号
Route::get('/verifcation/{token}','Shop_apiController@verifcation');

//登录接口
Route::post('/login','Shop_apiController@login');

//用户名发送密码
Route::post('/reset_pwd','Shop_apiController@reset_pwd');

//修改密码
Route::get('/up_pwd/{id}','Shop_apiController@up_pwd')->middleware('token');

//个人信息展示
Route::get('/personal_is_show/{id}','Shop_apiController@personal_is_show')->middleware('token');

//修改查询个人信息
Route::get('/up_personal/{id}','Shop_apiController@up_personal')->middleware('token');

//修改个人信息
Route::post('/ups_personal','Shop_apiController@ups_personal')->middleware('token');

//我的未读消息接口
Route::get('/wo_unread_news/{id}','Shop_apiController@wo_unread_news')->middleware('token');


//我的消息接口
Route::get('/wo_news/{id}','Shop_apiController@wo_news')->middleware('token');

//我的钱包-积分
Route::get('/integral/{id}','Shop_apiController@integral')->middleware('token');

//分组
// Route::group('/login','Shop_apiController@reset_pwd');