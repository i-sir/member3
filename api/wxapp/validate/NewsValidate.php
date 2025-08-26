<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"News",
    *     "name_underline"   =>"news",
    *     "table_name"       =>"news",
    *     "validate_name"    =>"NewsValidate",
    *     "remark"           =>"消息管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 16:46:01",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, News);
    * )
    */

class NewsValidate extends Validate
{

protected $rule = ['name'=>'require',
'image'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
'image.require'=>'封面不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
