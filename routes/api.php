<?php

use Illuminate\Http\Request;

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


