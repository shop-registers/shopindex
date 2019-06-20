<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goodstype;
use App\Models\Goods;

class GoodsTypeController extends Controller
{
	public function getgoodstype(Request $request)
	{
		$data = Goodstype::all()->toArray();
		$data =$this->getTree($data);
		return json_encode($data);
	}

	public function getgoods(Request $request,$id=null)
	{
		$url = 'F:/phpStudy/PHPTutorial/WWW/SHOP/public/img/';
		$data = Goods::where('type_id',$id)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
		return json_encode($data);
	}

	/**
	 * 分类商品1
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function getgoods1(Request $request,$id=null)
	{
		if($id == null){
			$data = Goods::where('type_id',1)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}else{
			$data = Goods::where('type_id',$id)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}
	}

	/**
	 * 分类商品2
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function getgoods2(Request $request,$id=null)
	{
		if($id == null){
			$data = Goods::where('type_id',2)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}else{
			$data = Goods::where('type_id',$id)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}
	}

	/**
	 * 分类商品3
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function getgoods3(Request $request,$id=null)
	{
		if($id == null){
			$data = Goods::where('type_id',3)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}else{
			$data = Goods::where('type_id',$id)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}
	}

	/**
	 * 分类商品4
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function getgoods4(Request $request,$id=null)
	{
		if($id == null){
			$data = Goods::where('type_id',4)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}else{
			$data = Goods::where('type_id',$id)
				->select('id','good_name','good_desc','good_price','good_img')
				->get();
			return json_encode($data);
		}
	}

	/**
	 * 递归
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function getTree($data){
		$arr = array();
		$tree = array();
		//循环重新排列
		foreach($data as $key){
			$arr[$key['id']] = $key;
		}

		foreach ($arr as $key => $value) {
			if($value['f_id'] > 0){
				//不是根节点的将自己的地址放到父级的child节点
				$arr[$value['f_id']]['child'][] = &$arr[$key];
			}else{
				//根节点直接把地址放到新数组中
				$tree[] = &$arr[$value['id']];
			}
		}
		return $tree;
	}

}