<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop_admin_comment extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $table = 'shop_admin_comment';

     /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;
	/**
     * 模型中日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

}
