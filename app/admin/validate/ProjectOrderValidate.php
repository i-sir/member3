<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ProjectOrder",
    *     "name_underline"   =>"project_order",
    *     "table_name"       =>"project_order",
    *     "validate_name"    =>"ProjectOrderValidate",
    *     "remark"           =>"项目报名",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 17:48:37",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ProjectOrder);
    * )
    */

class ProjectOrderValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
