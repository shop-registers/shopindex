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
        $data['good_id']=$request->input('good_id');
    	$data['good_name']=$request->input('good_name');
		/*$data['user_id']=$request->session()->get('user_id');*/
        $data['user_id']=1;
		$data['addtime']=date('Y-m-d H:i:s',time());;
		$data['good_price']=$request->input('good_price');
		$data['goods_number']=$request->input('text_box');
		$data['good_sku_code']=$request->input('sku_code');
        
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
    	$code=date('YmdHis',time()).$sku_code.$goods_id;
    	return $code;
    }
    /**
     * 确认下单
     */
	public function add_order(Request $request){
		$data['sku_code']=$request->input('sku_code');//sku编码
		$data['goods_id']=$request->input('good_id');//商品的ID
		/*$data['customer_name']=$request->session()->get('user_id');*///用户ID
        $data['customer_name']=1;
		$data['order_num']=$request->input('text_box');//商品数量
		$data['order_money']=$request->input('good_price');//订单金额
		$data['payment_money']=$request->input('good_price');//支付金额
		$data['create_time']=date('Y-m-d H:i:s',time());//时间
		$data['order_sn']=$this->create_order_code($data['goods_id'],$data['sku_code']);//订单编码
		$res=Order_master::insertGetId($data);
		if($res){
			json(40015,"添加订单成功",$data['order_sn']);
		}else{
			json(40016,"添加订单失败");
		}
    }
    /**
     * 商品分类页面展示
     */
    public function goodtype_show(Request $request){
    	$id=$request->input('id');
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
    public function payorder(Request $request){
        $code=$request->input('code');
        $user_id=$request->session()->get('user_id');
        $codearr=explode(',',$code);
        foreach($codearr as $k=>$v){
            $res['orderinfo'][]=Order_master::where('order_sn',$v)->select('')->get()->toArray();
        }
        $res['user_integral']=User::where('id',$user_id)->select('integral')->get();
        $res['address']=Address::where('u_id',$user_id)->get();
        if(count($res['orderinfo'])==0){
            json(40011,"查询成功",$res);
        }else{
            json(40014,"查询失败");
        }
    }
    public function payment_success(Request $Request){
        $order_sn=$request->input('order_sn');
        $res=Order_master::where('order_sn',$orderinfo)-.update(['order_status'=>1]);
        if($res){
            json(40015,"支付成功");
        }else{
            json(40016,"支付失败");
        }
    }
}
