<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Project",
    *     "name_underline"   =>"project",
    *     "table_name"       =>"project",
    *     "validate_name"    =>"ProjectValidate",
    *     "remark"           =>"项目预定",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 17:09:37",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Project);
    * )
    */

class ProjectValidate extends Validate
{

protected $rule = ['name'=>'require',
'price'=>'require',
'image'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'price.require'=>'价格不能为空!',
'image.require'=>'封面不能为空!',
];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
