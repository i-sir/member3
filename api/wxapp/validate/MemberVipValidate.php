<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"MemberVip",
    *     "name_underline"   =>"member_vip",
    *     "table_name"       =>"member_vip",
    *     "validate_name"    =>"MemberVipValidate",
    *     "remark"           =>"会员管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:51:29",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, MemberVip);
    * )
    */

class MemberVipValidate extends Validate
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
