<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"WorkOrder",
    *     "name_underline"   =>"work_order",
    *     "table_name"       =>"work_order",
    *     "validate_name"    =>"WorkOrderValidate",
    *     "remark"           =>"报名岗位",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 15:12:05",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, WorkOrder);
    * )
    */

class WorkOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
