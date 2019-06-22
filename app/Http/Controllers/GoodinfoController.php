<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Good_attr;
use App\Models\Goods;
use App\Models\Goods_img;
use App\Models\Collect;
use App\Models\Shopcart;
use App\Models\Order_master;
use App\Models\Goods_sku;

class GoodinfoController extends Controller
{
    /**
     * 商品的详情页展示 
     */
    public function good_showinfo(Request $request){
    	$good_id=$request->input('good_id');
    	$res['good']=Goods::where('id',$good_id)->get()->toArray();
    	$res['attr']=Good_attr::where('good_id',$good_id)->get();
    	foreach ($res['attr'] as $v) {
		    $v->attr_desc=explode(',',$v->attr_desc);
		}
    	$res['goodimg']=Goods_img::where('goods_id',$good_id)->select('img_src')->get()->toArray();

    	if(count($res['good'])!=0){
			json(40011,"查询成功",$res);
    	}else{
    		json(40014,"查询失败");
    	}
    }
    /**
     * 添加收藏
     */
    public function add_collect(Request $request){
		$data['good_id']=$request->input('goods_id');
		$data['user_id']=$request->input('user_id');
		$data['addtime']=time();
		$res=Collect::insert($data);
		if($res){
			json(40012,"添加收藏成功");
		}else{
			json(40013,"添加收藏失败");
		}
    }
    /**
     * 添加到购物车
     */
    public function add_shopcart(Request $request){
        $data['good_id']=$request->input('goods_id');
    	$data['good_name']=$request->input('goods_name');
		$data['user_id']=$request->input('user_id');
		$data['addtime']=$request->input('addtime');
		$data['good_price']=$request->input('good_price');
		$data['good_number']=$request->input('good_number');
		$data['good_sku_code']=$request->input('good_sku_code');
		$res=Shopcart::insert($data);
		if($res){
			json(40012,"添加购物车成功");
		}else{
			json(40013,"添加购物车失败");
		}
    }
    /**
     * 生成订单号
     */
    public function create_order_code($goods_id,$sku_code){
    	$code=data('YmdHis',time()).$sku_code.$goods_id;
    	return $code;
    }
    /**
     * 确认下单
     */
	public function add_order(Request $request){
		$data['sku_code']=$request->input('sku_code');//sku编码
		$data['goods_id']=$request->input('goods_id');//商品的ID
		$data['customer_name']=$request->input('customer_name');//收货人ID
		$data['shipping_user']=$request->input('shipping_user');//收货人姓名
		$data['shipping_tel']=$request->input('shipping_tel');//收货人电话
		$data['order_num']=$request->input('order_num');//商品数量
		$data['order_money']=$request->input('order_money');//订单金额
		$data['district_money']=$request->input('district_money');//优惠价格
		$data['shipping_money']=$request->input('shipping_money');//运费价格
		$data['payment_money']=$request->input('payment_money');//支付金额
		$data['create_time']=date('Y-m-d H:i:s',time());//时间
		$data['order_sn']=$this->create_order_code($goods_id,$sku_code);//订单编码
		$res=Order_master::insert($data);
		if($res){
			json(40015,"添加订单成功");
		}else{
			json(40016,"添加订单失败");
		}
    }
    /**
     * 商品分类页面展示
     */
    public function goodtype_show(Request $request,$id=null){
    	
    	$res=Goods::where('type_id',$id)->get();
    	if(isset($res[0])){
            json(40011,"查询成功",$res);
        }else{
            json(40014,"查询失败");
        }
    }
    /**
     * ajax改变商品价格
     */
    public function goodattr_change(Request $request){
    	$attr=$request->input('str');
    	$attrinfo=Goods_sku::where('sku_desc',"$attr")->select('sku_id','price','inventory')->get();
    	if(isset($attrinfo[0])){
            json(40011,"查询成功",$attrinfo);
        }else{
            json(40014,"查询失败");
        }
    }
    /**
     * 商品评论内容
     */
    public function good_comment(Request $request){
    	$good_id=$request->input('goods_id');
    	$comment=Comment::where('objectid',$good_id)->join('user','comment.userid=user.id')->get();
    	if(isset($comment[0])){
			json(40011,"查询成功",$comment);
    	}else{
    		json(40014,"查询失败");
    	}
    }
}
