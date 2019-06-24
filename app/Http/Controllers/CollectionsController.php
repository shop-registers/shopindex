<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Models\Controllers;
use App\Http\Controllers\Controller;

class CollectionsController extends Controller
{
    public function my_collections(Request $request)
    {
    	//获取session中存储的用户id(user_id)
        $request->session()->put('user_id','1');  //设置session 
        $user_id=$request->session()->get('user_id'); //获取session值
        $good_id=$request->input("good_id");
        $good_name=$request->input("good_name");
        $good_img=$request->input("good_img");
        $good_price=$request->input("good_price");
        $addtime=time();
        $res=Controllers::
    	return json_encode($addtime);
    }
}
