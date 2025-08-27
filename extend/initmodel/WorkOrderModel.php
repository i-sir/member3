<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"WorkOrder",
    *     "name_underline"   =>"work_order",
    *     "table_name"       =>"work_order",
    *     "model_name"       =>"WorkOrderModel",
    *     "remark"           =>"报名岗位",
    *     "author"           =>"",
    *     "create_time"      =>"2025-08-27 15:12:05",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\WorkOrderModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class WorkOrderModel extends Model{

	protected $name = 'work_order';//报名岗位

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
