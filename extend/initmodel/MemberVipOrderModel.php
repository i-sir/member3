<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"MemberVipOrder",
    *     "name_underline"   =>"member_vip_order",
    *     "table_name"       =>"member_vip_order",
    *     "model_name"       =>"MemberVipOrderModel",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:51:48",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\MemberVipOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class MemberVipOrderModel extends Model{

	protected $name = 'member_vip_order';//订单管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
