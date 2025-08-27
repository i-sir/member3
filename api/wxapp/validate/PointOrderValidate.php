<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"PointOrder",
    *     "name_underline"   =>"point_order",
    *     "table_name"       =>"point_order",
    *     "validate_name"    =>"PointOrderValidate",
    *     "remark"           =>"订单管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:44:05",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, PointOrder);
    * )
    */

class PointOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
