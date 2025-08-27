<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"PointOrder",
    *     "name_underline"   =>"point_order",
    *     "table_name"       =>"point_order",
    *     "model_name"       =>"PointOrderModel",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:44:05",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\PointOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class PointOrderModel extends Model{

	protected $name = 'point_order';//订单管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
