<?php

namespace App\Http\Controllers;

use DB;
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
    	if(!empty($data['customer_name']) )
    	{
    		$data = DB::table('shop_admin_order_master')->where('customer_name',$data['customer_name'])
                                    ->join('shop_admin_order_detail','shop_admin_order_master.order_id','=','shop_admin_order_detail.order_id')
                                    ->join('shop_admin_goods_sku','shop_admin_order_detail.sku_code','=','shop_admin_goods_sku.sku_id')
                                    ->join('shop_admin_goods','shop_admin_order_detail.product_id','=','shop_admin_goods.id')

                                    ->select()->get()->toArray();
            // echo "<pre>";
            // print_r($data);die;
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
    	if(!empty($data['customer_name']) )
    	{
            $data = DB::table('shop_admin_order_master')->where([['customer_name','=',$data['customer_name']] ,['order_status','=',3] ])
                                    ->join('shop_admin_order_detail','shop_admin_order_master.order_id','=','shop_admin_order_detail.order_id')
                                    ->join('shop_admin_goods_sku','shop_admin_order_detail.sku_code','=','shop_admin_goods_sku.sku_id')
                                    ->join('shop_admin_goods','shop_admin_order_detail.product_id','=','shop_admin_goods.id')

                                    ->select()->get()->toArray();
    		// print_r($data);die;
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
    	if(!empty($data['customer_name']) )
    	{
    		$data = DB::table('shop_admin_order_master')->where([['customer_name','=',$data['customer_name']] ,['order_status','=',1] ])
                                    ->join('shop_admin_order_detail','shop_admin_order_master.order_id','=','shop_admin_order_detail.order_id')
                                    ->join('shop_admin_goods_sku','shop_admin_order_detail.sku_code','=','shop_admin_goods_sku.sku_id')
                                    ->join('shop_admin_goods','shop_admin_order_detail.product_id','=','shop_admin_goods.id')

                                    ->select()->get()->toArray();
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
    	if(!empty($data['customer_name']) )
    	{
    		$data = DB::table('shop_admin_order_master')->where([['customer_name','=',$data['customer_name']] ,['order_status','=',0] ])
                                    ->join('shop_admin_order_detail','shop_admin_order_master.order_id','=','shop_admin_order_detail.order_id')
                                    ->join('shop_admin_goods_sku','shop_admin_order_detail.sku_code','=','shop_admin_goods_sku.sku_id')
                                    ->join('shop_admin_goods','shop_admin_order_detail.product_id','=','shop_admin_goods.id')

                                    ->select()->get()->toArray();
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
