<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;

//订单接口
class FullOrderController extends Controller
{
    //全部订单接口
    public function FullOrder(Request $request)
    {
    	$data = $request->all();
    	if(!empty($data['customer_id']) )
    	{
    		$data = Order::where('customer_id',$data['customer_id'])->select()->get()->toArray();
    		if($data)
    		{
    			return response()->json([Response::HTTP_OK,'msg'=>'查询订单成功','data'=>$data]);
    		}
    		else
    		{
    			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'订单查询失败']);
    		}
    	}
    	else
    	{
    		return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'用户不存在']);
    	}
    }
    //待评论订单接口
    public function CommentedOrder(Request $request)
    {
    	$data = $request->all();
    	if(!empty($data['customer_id']) )
    	{
    		$data = Order::where([['customer_id','=',$data['customer_id']] ,['order_status','=',$data['order_status']] ])->select()->get()->toArray();
    		if($data)
    		{
    			return response()->json([Response::HTTP_OK,'msg'=>'查询订单成功','data'=>$data]);
    		}
    		else
    		{
    			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'订单查询失败']);
    		}
    	}
    	else
    	{
    		return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'用户不存在']);
    	}
    }
    //待收货接口
    public function ReceiveddOrder(Request $request)
    {
    	$data = $request->all();
    	if(!empty($data['customer_id']) )
    	{
    		$data = Order::where([['customer_id','=',$data['customer_id']] ,['order_status','=',$data['order_status']] ])->select()->get()->toArray();
    		if($data)
    		{
    			return response()->json([Response::HTTP_OK,'msg'=>'查询订单成功','data'=>$data]);
    		}
    		else
    		{
    			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'订单查询失败']);
    		}
    	}
    	else
    	{
    		return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'用户不存在']);
    	}
    }
    //待支付接口
    public function PaidOrder(Request $request)
    {
    	$data = $request->all();
    	if(!empty($data['customer_id']) )
    	{
    		$data = Order::where([['customer_id','=',$data['customer_id']] ,['order_status','=',$data['order_status']] ])->select()->get()->toArray();
    		if($data)
    		{
    			return response()->json([Response::HTTP_OK,'msg'=>'查询订单成功','data'=>$data]);
    		}
    		else
    		{
    			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'订单查询失败']);
    		}
    	}
    	else
    	{
    		return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'用户不存在']);
    	}
    }
}
