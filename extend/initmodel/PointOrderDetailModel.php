<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"PointOrderDetail",
    *     "name_underline"   =>"point_order_detail",
    *     "table_name"       =>"point_order_detail",
    *     "model_name"       =>"PointOrderDetailModel",
    *     "remark"           =>"订单详情",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:44:22",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\PointOrderDetailModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class PointOrderDetailModel extends Model{

	protected $name = 'point_order_detail';//订单详情

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
