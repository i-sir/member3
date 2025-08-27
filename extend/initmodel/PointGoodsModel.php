<?php

namespace initmodel;

/**
 * @AdminModel(
 *     "name"             =>"PointGoods",
 *     "name_underline"   =>"point_goods",
 *     "table_name"       =>"point_goods",
 *     "model_name"       =>"PointGoodsModel",
 *     "remark"           =>"商品管理",
 *     "author"           =>"",
 *     "create_time"      =>"2025-08-27 16:43:18",
 *     "version"          =>"1.0",
 *     "use"              => new \initmodel\PointGoodsModel();
 * )
 */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class PointGoodsModel extends Model
{

    protected $name = 'point_goods';//商品管理

    //软删除
    protected $hidden            = ['delete_time'];
    protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
