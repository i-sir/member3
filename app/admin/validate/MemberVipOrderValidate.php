<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"MemberVipOrder",
    *     "name_underline"   =>"member_vip_order",
    *     "table_name"       =>"member_vip_order",
    *     "validate_name"    =>"MemberVipOrderValidate",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:51:48",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, MemberVipOrder);
    * )
    */

class MemberVipOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
