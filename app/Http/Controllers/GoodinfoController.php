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
use App\Models\Users;
use App\Models\Address;
use App\Models\Order_detail;
use App\Models\Nationwide_address;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class GoodinfoController extends Controller
{
    /**
     * 商品的详情页展示 
     */
    public function good_showinfo(Request $request,$id=null){
    	
    	$res['good']=Goods::where('id',$id)->get()->toArray();
    	$res['attr']=Good_attr::where('good_id',$id)->get();
        
    	foreach ($res['attr'] as $v) {
		    $v->attr_desc=explode(',',$v->attr_desc);
		}
    	$res['goodimg']=Goods_img::where('goods_id',$id)->select('img_src')->get()->toArray();
        $res['address']=Nationwide_address::where('parentid','=',100000)->get();
        
    	if(count($res['good'])!=0){
			return Response()->json(['code'=>40011,'msg'=>"查询成功",'data'=>$res]);
    	}else{
    		return Response()->json(['code'=>40014,'msg'=>"查询失败"]);
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
			return Response()->json(['code'=>40012,'msg'=>"添加收藏成功"]);
		}else{
			return Response()->json(['code'=>40013,'msg'=>"添加收藏失败"]);
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
        $data['sku_desc']=$request->input('sku_desc');
        $data['addtime']=time();
		$res=Shopcart::insert($data);
		if($res){
			return Response()->json(['code'=>40012,'msg'=>"添加购物车成功"]);
		}else{
			return Response()->json(['code'=>40013,'msg'=>"添加购物车失败"]);
		}
    }
    /**
     * 生成订单号
     */
    public function create_order_code($user_id,$sku_code=null){
    	$code=date('YmdHis',time()).$user_id.rand(1111,9999).$sku_code;
        $res=Order_master::where('order_sn',$code)->select('order_sn')->get();
        if(count($res)==0){
            return $code;    
        }else{
            $this->create_order_code($user_id);
        }
    }
    /**
     * 确认下单
     */
	public function add_order(Request $request){
		/*$data['customer_name']=$request->session()->get('user_id');*///用户ID
        $data['customer_name']=1;
        $data['order_sn']=$this->create_order_code($data['customer_name']);//订单编码
        $data['create_time']=date('Y-m-d H:i:s',time());//时间
        $res=Order_master::insertGetId($data);
        if(!$res){
            return Response()->json(['code'=>40016,'msg'=>"添加订单失败"]);return;
        }
        $arr['order_id']=$res;
        $arr['product_id']=$request->input('good_id');
		$arr['product_cnt']=$request->input('text_box');//商品数量
		$arr['product_price']=$request->input('good_price');//订单金额
        $arr['sku_code']=$request->input('sku_code');
		$arr['order_money']=$request->input('good_price')*$arr['product_cnt'];//支付金额
        $arr['child_order_sn']='cd'.$this->create_order_code($data['customer_name'],$arr['sku_code']);
        $result=Order_detail::insertGetId($arr);
		if($result){
            $last=base64_encode("order_sn=".$data['order_sn']."&id=".$res);//总的订单号和主订单的ID
			return Response()->json(['code'=>40015,'msg'=>"添加订单成功",'data'=>$last]);
		}else{
			return Response()->json(['code'=>40016,'msg'=>"添加订单失败"]);
		}
    }
    /**
     * 商品分类页面展示
     */
    public function goodtype_show(Request $request,$id=null){
    	
    	$res=Goods::where('type_id',$id)->get();
    	if(isset($res[0])){
            return Response()->json(['code'=>40011,'msg'=>"查询成功",'data'=>$res]);
        }else{
            return Response()->json(['code'=>40014,'msg'=>"查询失败"]);
        }
    }
    /**
     * ajax改变商品价格
     */
    public function goodattr_change(Request $request){
    	$attr=$request->input('str');
        $good_id=$request->input('good_id');
    	$attrinfo=Goods_sku::where('sku_desc',"$attr")->where('goods_id',"$good_id")->select('sku_id','price','inventory')->get();
    	if(isset($attrinfo[0])){
            return Response()->json(['code'=>40011,'code'=>"查询成功",'data'=>$attrinfo]);
        }else{
            return Response()->json(['code'=>40014,'msg'=>"查询失败"]);
        }
    }
    /**
     * 商品评论内容
     */
    public function good_comment(Request $request){
    	$good_id=$request->input('goods_id');
    	$res['comment']=Comment::where('objectid',$good_id)->get();
        $res['total']=count($res['comment']);
        $res['good']=0;
        $res['common']=0;
        $res['gap']=0;
        foreach ($res['comment'] as $v) {
            switch ($v->classtype) {
                case '1':
                    $v="好评";
                    $res['good']++;
                    break;
                case '2':
                    $res['common']++;
                    $v="中评";
                    break;
                case '3':
                    $res['gap']++;
                    $v="差评";
                    break;
            }
        };
    	if(isset($res['comment'][0])){
			return Response()->json(['code'=>40011,'msg'=>"查询成功",'data'=>$res]);
    	}else{
    		return Response()->json(['code'=>40014,'msg'=>"查询失败"]);
    	}
    }
    public function payorder(Request $request){
        $code=$request->input('code');
        parse_str(base64_decode($code),$arr);
        $res['orderinfo']['order'][]=Order_detail::where('order_id',$arr['id'])->leftjoin('goods','order_detail.product_id','=','goods.id')->leftjoin('goods_sku','order_detail.sku_code','=','goods_sku.sku_id')->select('order_detail.*','goods.good_name','goods_sku.sku_desc')->get()->toArray();
        /*$user_id=$request->session()->get('user_id');*/
        $user_id=1;
        $res['orderinfo']['total_price']=0;
        foreach($res['orderinfo']['order'] as $k=>$v){
            foreach ($v as $key => $value) {
                $res['orderinfo']['total_price']+=$value['order_money'];
            }
        }
        $res['user_integral']=Users::where('id',$user_id)->select('integral')->get();
        $res['address']=Address::where('u_id',$user_id)->get();
        $res['order_sn']=$arr['order_sn'];
        if(count($res['orderinfo'])!=0){
            return Response()->json(['code'=>40011,'msg'=>"查询成功",'data'=>$res]);
        }else{
            return Response()->json(['code'=>40014,'msg'=>"查询失败"]);
        }
    }
    public function payment_success(Request $request){
        $order_sn=$request->input('order_sn');
        $a=Order_master::where('order_sn',$order_sn)->select('order_status')->get()->toArray();
        if($a[0]['order_status']==1){
            return json(40017,"已支付，请勿重复提交");
        }else if($a[0]['order_status']==0){
            $child_order_sn=explode(',',$request->input('child_order_sn'));
            foreach ($child_order_sn as $key => $value) {
                $result[]=Order_detail::where('child_order_sn',$child_order_sn)->select('product_id','sku_code')->get()->toArray();    
            }
            $data['shipping_user']=$request->input('shipping_user');
            $data['shipping_tel']=$request->input('shipping_tel');
            $data['address']=$request->input('address');
            $data['order_num']=$request->input('order_num');
            $pay_type=$request->input('pay_list');
            switch ($pay_type) {
                case '微信':
                    $data['payment_method']=3;
                    break;
                case '支付宝':
                    $data['payment_method']=2;
                    break;
                case '网银':
                    $data['payment_method']=1;
                    break;
            }
            $data['order_money']=$request->input('total_price');
            $data['payment_money']=$request->input('price');
            $data['pay_time']=date('Y-m-d H:i:s',time());
            $data['order_status']=1;
            $res=DB::transaction(function () use ($order_sn,$data,$result,$child_order_sn) {
                Order_master::where('order_sn',$order_sn)->update($data);
                foreach($child_order_sn as $k=>$v){
                    Order_detail::where('child_order_sn',$v)->update(['product_cnt'=>$data['order_num'],'order_money'=>$data['order_money'],'payment_money'=>$data['payment_money']]);
                }
                foreach ($result as $key => $value) {
                    Goods_sku::where('sku_id',$value[0]['sku_code'])->decrement('inventory',$data['order_num']);
                    Goods::where('id',$value[0]['product_id'])->decrement('good_inventory',$data['order_num']);    
                }
                
            });
            if($res==null){
                return Response()->json(['code'=>40015,'msg'=>"支付成功",'data'=>$order_sn]);
            }else{
                return Response()->json(['code'=>40016,'msg'=>"支付失败"]);
            }
        }
        
    }
    public function del_address(Request $request){
        $id=$request->input('id');
        $res=Address::where('address_id',$id)->delete();
        if($res){
            return Response()->json(['code'=>40017,'msg'=>"删除成功"]);
        }else{
            return Response()->json(['code'=>40018,'msg'=>"删除失败"]);
        }
    }
    public function set_defaule_address(Request $request){
        $id=$request->input('id');
        $address=Address::where('default',1)->get()->toArray();
        $res=DB::transaction(function () use ($id,$address) {
            Address::where('address_id',$address[0]['address_id'])->update(['default'=>0]);
            Address::where('address_id',$id)->update(['default'=>1]);
        });
        if($res){
            return Response()->json(['code'=>40019,'msg'=>"更改成功"]);
        }else{
            return Response()->json(['code'=>40020,'msg'=>"更改失败"]);
        }
    }
    public function get_success(Request $request){
        $code=$request->input('code');
        $data=Order_master::where('order_sn',$code)->select('shipping_tel','shipping_user','address','payment_money')->get();
        if($data){
            return Response()->json(['code'=>40015,'msg'=>'查询成功','data'=>$data]);
        }else{
            return Response()->json(['code'=>40016,'msg'=>'查询失败']);
        }
    }
}