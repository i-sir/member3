<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Dynamic",
    *     "name_underline"   =>"dynamic",
    *     "table_name"       =>"dynamic",
    *     "model_name"       =>"DynamicModel",
    *     "remark"           =>"动态管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:25:50",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\DynamicModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class DynamicModel extends Model{

	protected $name = 'dynamic';//动态管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
