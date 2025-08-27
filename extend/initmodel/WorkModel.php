<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Work",
    *     "name_underline"   =>"work",
    *     "table_name"       =>"work",
    *     "model_name"       =>"WorkModel",
    *     "remark"           =>"岗位管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 15:11:44",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\WorkModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class WorkModel extends Model{

	protected $name = 'work';//岗位管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
