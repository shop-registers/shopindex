<?php
namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users_token;
use App\Models\Shop_admin_comment;
use Illuminate\Support\Facades\Cache;
// use cache;
class Shop_apiController extends Controller
{
    //注册
    public function register(Request $request)
    {
        //获取接收数据
        $data = $request->all();
        // 验证器验证规则
        $rules=[
            'name'=>'unique:users,name|required|alpha_dash|between:2,30',
            'pwd'=>'required|between:6,30',
            'pwd_cc'=>'required|same:pwd',
            'email'=>'unique:users,email|required|email',
        ];
        $message=[
            'name.required'=>'名字不能为空',
            'name.between'=>'最少两个汉子',
            'name.alpha_dash'=>'必须汉子',
            'name.unique'=>'名字不能重复',
            'pwd.required'=>'密码不能为空',
            'pwd_cc.same'=>'密码必须一致',
            'pwd_cc.required'=>'确认密码不能为空',
            'pwd.between'=>'密码最少6位字符最多30个字符',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱不能重复注册',
        ];
        $validator=Validator::make($data,$rules,$message);
        if(!$validator->passes()){
            //返回错误信息
            $validatorErrs = $validator->errors()->first();
            return $this->error('001',$validatorErrs);
        }
        unset($data['pwd_cc']);
        $email = $data['email'];
        //添加用户信息
        $data['last_time'] = time();
        $create_data = User::insertGetId($data);
        if(!$create_data)
        {
            return $this->error('1002','添加信息失败');
        }
        $token = "激活连接"."www.api.com/api/verifcation/".$create_data;
        // 发送邮箱验证
        return $this->sendMail($email,$token);
        
    }

    //账号激活
    public function verifcation(Request $request)
    {
        $id = $request->route('token');
        $data = User::where('id',$id)->update(['status'=>'1']);
        if($data)
        {
            echo "激活成功";                                                                                  
        }
        else
        {
            echo "您已经激活成功了";
        }
    }

    //登录接口
    public function login(Request $request)
    {
        //获取登录数据
        $data = $request->all();
        $name = $data['name'];
        $pwd = $data['pwd'];
        if(empty($data['name']) || empty($data['pwd']))
        {
            return $this->error('1002','参数缺失');
        }
        // 判断登录数据是否一致
        $data = User::where('name',$name)->where('pwd',$pwd)->first();
        $user_id = $data['id'];
        if(empty($data['name']) || empty($data['pwd']))
        {
            return $this->error('1003','用户名、密码不正确！');
        }
        if($data['status'] == 0)
        {
            return $this->error('1004','请激活您的账号！');
        }
        //生成token
        $res = Users_token::where('user_id',$user_id)->first();
        if(!empty($res))
        {
            $token = md5($name.$pwd.rand(999,111));
            // $data = Cache::forever('key',$token);
            $data = Cache::put('key',$token,744*60);
            $res = Users_token::where('user_id',$user_id)->update(['token'=>$token]);
            return $this->success(['user_id'=>$user_id,'token'=>$token]);
            //实例化
        }
        else
        {
            $token = md5($name.$pwd.rand(999,111));
            // $data = Cache::forever('key',$token);
            $data = Cache::put('key',$token,744*60);
            $res = Users_token::insert(['user_id'=>$user_id,'token'=>$token]);
            return $this->success(['user_id'=>$user_id,'token'=>$token]);
        }
        
    }

    //用户名发送邮箱
    public function reset_pwd(Request $request)
    {
        //获取用户名称发送到用户的邮箱
        $data = $request->all();
        $name = User::where('name',$data['name'])->first();
        if(empty($name))
        {
            return $this->error('1001','请求参数缺失');
        }
        $email = $name['email'];
        $token = "www.api.com/api/up_pwd/".$name['id'];
        return $this->success('1');
        return $this->sendMail($email,$token);

    }

    //通过邮箱重置密码修改密码
    public function up_pwd(Request $request)
    {
        //获取穿过来的数据
        $id = $request->route('id');
        $res = User::where('id',$id)->first();
        if(empty($res))
        {
            return $this->error('1004','没有该数据');
        }
        $data = $request->all();
        $pwd = $data['pwd'];
        $rpwd = $data['pwd_cc'];
        $pwd_len = strlen($rpwd);
        //判断密码和确认密码
        if(empty($data['pwd']) || empty($data['pwd_cc']))
        {
            return $this->error('1001','请求参数缺失');
        }
        //查找用户数据判断是否是用户的真实密码
        if(!$res['pwd'] == $pwd)
        {
            return $this->error('1003','密码不正确');
        }
        // 判断字符
        if($pwd_len < 8)
        {
            return $this->error('1005','最小8个字符');
        }
        $data = User::where('id',$id)->update(['pwd'=>$rpwd]);
        if($data)
        {
            return $this->success('1');
        }
    }

    //个人信息展示
    public function personal_is_show(Request $request)
    {
        //获取穿过来的数据
        $id = $request->route('id');
        if(empty($id))
        {
            return $this->error('1006','没有用户id');
        }
        $data = User::where('id',$id)->first()->toArray();
        return $this->success($data);
    }

    //修改查询个人信息
    public function up_personal(Request $request)
    {
        $id = $request->route('id');
        $data = User::where('id',$id)->first()->toArray();
        return $this->success($data);
    }

    //修改用户信息
    public function ups_personal(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
         $rules=[
            'name'=>'required|alpha_dash|between:2,30',
            'pwd'=>'required|between:6,30',
            'email'=>'unique:users,email|required|email',
        ];
        $message=[
            'name.required'=>'名字不能为空',
            'name.between'=>'最少两个汉子',
            'name.alpha_dash'=>'必须汉子',
            'pwd.required'=>'密码不能为空',
            'pwd.between'=>'密码最少6位字符最多30个字符',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱不能重复注册',
        ];
        $validator=Validator::make($data,$rules,$message);
        if(!$validator->passes()){
            //返回错误信息
            $validatorErrs = $validator->errors()->first();
            return $this->error('001',$validatorErrs);
        }
        $arr = ['name'=>$data['name'],'pwd'=>$data['pwd'],'email'=>$data['email'],'image'=>$data['image'],'sex'=>$data['sex'],'birth'=>$data['birth']];
        $res = User::where('id',$id)->update($arr);
        if($res)
        {
            return $this->success('修改成功');
        }
    }

    //我的未读消息
    public function wo_unread_news(Request $request)
    {
        $id = $request->route('id');
        if(empty($id))
        {
            $this->error('1001','必须输入用户id');
        }
        $res = Shop_admin_comment::select('userid','objectid','addtime','content','username','parentid')->where(['status'=>'0','userid'=>$id])->get();
            return $this->success($res);
    }

    //我的已读消息
    public function wo_news(Request $request)
    {
        $id = $request->route('id');
        if(empty($id))
        {
            $this->error('1001','必须输入用户id');
        }
        $res = Shop_admin_comment::select('userid','objectid','addtime','content','username','parentid')->where(['status'=>'1','userid'=>$id])->get();
            return $this->success($res);
    }

    //我的钱包-我的积分
    public function integral(Request $request)
    {
        $id = $request->route('id');
        $data = User::select('name','id','integral')->where(['id'=>$id])->first()->toArray();
        return $this->success($data);
    }
}