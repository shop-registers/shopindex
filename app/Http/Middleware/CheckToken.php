<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        if(!$token)
        {
            echo json_encode(['code'=>'222','msg'=>'header请求缺少token'],JSON_UNESCAPED_UNICODE);die;
          
        }
        //如果没有缓存提示报错
        $value = Cache::get('key');
        // print_r($value);die;
        if($token != $value)
        {
            echo json_encode(['code'=>'111','msg'=>'token不存在、token为空'],JSON_UNESCAPED_UNICODE);die;
        }
        return $next($request);
    }
}
