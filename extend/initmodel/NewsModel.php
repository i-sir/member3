<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"News",
    *     "name_underline"   =>"news",
    *     "table_name"       =>"news",
    *     "model_name"       =>"NewsModel",
    *     "remark"           =>"消息管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 16:46:01",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\NewsModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class NewsModel extends Model{

	protected $name = 'news';//消息管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
