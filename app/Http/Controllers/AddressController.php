<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Symfony\Component\HttpFoundation\Response;

//接扣地址
class AddressController extends Controller
{
    //我的地址列表
    public function Myaddress(Request $request)
 	{
 		//接受所有数据
 		$data = $request->all();
 		//判断用户id不能为空
 		if(!empty($data['u_id']))
 		{
 			if($data = Address::where('u_id',$data['u_id'])->select()->get()->toArray())
 			{
 				//查找成功 返回200 ok
 				return response()->json(['data'=>$data,Response::HTTP_OK,'msg'=>"查询成功"]);
 			}
 			else
 			{
 				//查找失败  返回404 未找到用户的id
 				return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'查询失败']);	
 			}
 		}
 		else
 		{
 			//没有接受到值  方法不允许
 			return response()->json([Response::HTTP_METHOD_NOT_ALLOWED,'msg'=>"请先登录"]);
 		}
 	}

 	//添加收货地址接口
 	public function AddAddress(Request $request)
 	{
 		if($request->all())
 		{
 			$data = $request->all();
 			if($info = Address::insert($data))
 			{
 				return response()->json(['info'=>$info,Response::HTTP_OK,'msg'=>'添加成功']);
 			}
 		}
 		else
 		{
 			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'添加失败']);
 		}
 	}
 	//地址删除接口
 	public function DelAddress(Request $request)
 	{
 		$data = $request->all();
 		if(!empty($data['address_id']))
 		{
 			$info = Address::where('address_id',$data['address_id'])->delete();
 			if($info)
 			{
 				return response()->json([Response::HTTP_OK,'msg'=>'删除成功']);
 			}
 			else
 			{
 				return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'删除失败']);
 			}
 			
 		}
 		else
 		{
 			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'用户不存在']);
 		}

 	}
 	// 地址编辑接口
 	public function UpdateAddress(Request $request)
 	{
 		$data = $request->all();	

 		$data = [
 			'address_id'=>$data['address_id'],
 			'u_id'=>$data['u_id'],
 			'token'=>$data['token'],
 			'address_name'=>$data['address_name'],
 			'address_tel'=>$data['address_tel'],
 			'address_location'=>$data['address_location'],
 			'address_detailed'=>$data['address_detailed'],
 		];

 		$info = Address::where('address_id',$data['address_id'])->update($data);
 		if($info)
 		{
 			return response()->json([Response::HTTP_OK,'msg'=>'修改成功']);
 		}
 		else
 		{
 			return response()->json([Response::HTTP_NOT_FOUND,'msg'=>'修改失败']);

 		}
 	}
}
