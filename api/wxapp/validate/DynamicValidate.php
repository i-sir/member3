<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Dynamic",
    *     "name_underline"   =>"dynamic",
    *     "table_name"       =>"dynamic",
    *     "validate_name"    =>"DynamicValidate",
    *     "remark"           =>"动态管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:25:50",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Dynamic);
    * )
    */

class DynamicValidate extends Validate
{

protected $rule = ['name'=>'require',
'price'=>'require',
'introduce'=>'require',
'image'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'price.require'=>'定金不能为空!',
'introduce.require'=>'介绍不能为空!',
'image.require'=>'封面不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
