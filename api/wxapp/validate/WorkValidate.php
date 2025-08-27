<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Work",
    *     "name_underline"   =>"work",
    *     "table_name"       =>"work",
    *     "validate_name"    =>"WorkValidate",
    *     "remark"           =>"岗位管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 15:11:44",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Work);
    * )
    */

class WorkValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
