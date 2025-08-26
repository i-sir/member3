<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Project",
    *     "name_underline"   =>"project",
    *     "table_name"       =>"project",
    *     "model_name"       =>"ProjectModel",
    *     "remark"           =>"项目预定",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 17:09:37",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ProjectModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ProjectModel extends Model{

	protected $name = 'project';//项目预定

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
