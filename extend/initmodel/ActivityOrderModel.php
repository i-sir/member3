<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ActivityOrder",
    *     "name_underline"   =>"activity_order",
    *     "table_name"       =>"activity_order",
    *     "model_name"       =>"ActivityOrderModel",
    *     "remark"           =>"活动报名",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-28 10:38:40",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ActivityOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ActivityOrderModel extends Model{

	protected $name = 'activity_order';//活动报名

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
