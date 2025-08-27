<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ProjectOrder",
    *     "name_underline"   =>"project_order",
    *     "table_name"       =>"project_order",
    *     "model_name"       =>"ProjectOrderModel",
    *     "remark"           =>"项目报名",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 17:48:37",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ProjectOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ProjectOrderModel extends Model{

	protected $name = 'project_order';//项目报名

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
