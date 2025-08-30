<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ActivityOrder",
    *     "name_underline"   =>"activity_order",
    *     "table_name"       =>"activity_order",
    *     "validate_name"    =>"ActivityOrderValidate",
    *     "remark"           =>"活动报名",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-28 10:38:40",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ActivityOrder);
    * )
    */

class ActivityOrderValidate extends Validate
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
