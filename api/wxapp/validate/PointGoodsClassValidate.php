<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"PointGoodsClass",
    *     "name_underline"   =>"point_goods_class",
    *     "table_name"       =>"point_goods_class",
    *     "validate_name"    =>"PointGoodsClassValidate",
    *     "remark"           =>"分类管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:43:33",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, PointGoodsClass);
    * )
    */

class PointGoodsClassValidate extends Validate
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
