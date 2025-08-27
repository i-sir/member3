<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"PointGoodsClass",
    *     "name_underline"   =>"point_goods_class",
    *     "table_name"       =>"point_goods_class",
    *     "model_name"       =>"PointGoodsClassModel",
    *     "remark"           =>"分类管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 16:43:33",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\PointGoodsClassModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class PointGoodsClassModel extends Model{

	protected $name = 'point_goods_class';//分类管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
