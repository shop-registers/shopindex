<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/27 0027
 * Time: 上午 11:19
 */
 
namespace App;
 
 
use DB;
 
class Mongodb
{
    public static function connectionMongodb($tables)
    {
        return $users = DB::connection('mongodb')->collection($tables);
    }

}