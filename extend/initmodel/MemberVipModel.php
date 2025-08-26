<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"MemberVip",
    *     "name_underline"   =>"member_vip",
    *     "table_name"       =>"member_vip",
    *     "model_name"       =>"MemberVipModel",
    *     "remark"           =>"会员管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-26 15:51:29",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\MemberVipModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class MemberVipModel extends Model{

	protected $name = 'member_vip';//会员管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
