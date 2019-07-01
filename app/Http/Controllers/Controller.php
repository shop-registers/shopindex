<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// 注意：先引入Laravel框架自带的邮件类
use Mail;
//$email 是要发送的邮件号，即接收方
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    //请求成功返回状态
    public function Success($data=array())
    {
		return response()->json([
	    'code' => '0',
	    'msg' => "请求成功",
	    'data' => $data
		]);
    }

    //请求错误返回数据
    public function Error($code,$msg)
    {
    	return response()->json([
		    'code' => $code,
		    'msg' => $msg
		]);
    }

    //$email 是要发送的邮件号，即接收方

	public function sendMail($email,$msg){

        //在闭包函数内部不能直接使用闭包函数外部的变量  使用use导入闭包函数外部的变量$email

        Mail::send('emails.test' , ['msg'=>$msg] , function($message)use($email){

                //设置主题

                $message->subject("小小怪！");

                //设置接收方

                $message->to($email);
               	echo json_encode(['code'=>'0','msg'=>'请求成功'],JSON_UNESCAPED_UNICODE);die;

        });

	}

}
