<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"PointGoods",
    *     "name_underline"   =>"point_goods",
    *     "table_name"       =>"point_goods",
    *     "validate_name"    =>"PointGoodsValidate",
    *     "remark"           =>"商品管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:43:18",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, PointGoods);
    * )
    */

class PointGoodsValidate extends Validate
{

protected $rule = ['goods_name'=>'require',
];




protected $message = ['goods_name.require'=>'商品名称不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
