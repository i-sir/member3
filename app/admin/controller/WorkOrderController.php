<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"WorkOrder",
 *     "name_underline"      =>"work_order",
 *     "controller_name"     =>"WorkOrder",
 *     "table_name"          =>"work_order",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"报名岗位",
 *     "author"              =>"",
 *     "create_time"         =>"2025-08-27 15:12:05",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\WorkOrderController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class WorkOrderController extends AdminBaseController
{

    // public function initialize(){
    //	//报名岗位
    //	parent::initialize();
    //	}


    /**
     * 首页基础信息
     */
    protected function base_index()
    {

    }

    /**
     * 编辑,添加基础信息
     */
    protected function base_edit()
    {


        $WorkInit = new \init\WorkInit();//work     (ps:InitController)
        $this->assign('type_list', $WorkInit->type);
        $this->assign('p_type_list', $WorkInit->p_type);
    }


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'WorkOrder',
     *     'name_underline'   => 'work_order',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '报名岗位',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $this->base_index();//处理基础信息

        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位    (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $WorkInit       = new \init\WorkInit();//work     (ps:InitController)

        $params = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params["keyword"]}%"];
        if ($params["test"]) $where[] = ["test", "=", $params["test"]];
        $where[] = ['status', 'in', [2, 3, 4, 5]];

        /** 导出数据 **/
        if ($params["is_export"]) $WorkOrderInit->export_excel($where, $params);

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $WorkOrderInit->get_list_paginate($where, $params);

        /** 数据渲染 **/
        $this->assign("list", $result);
        $this->assign("pagination", $result->render());//单独提取分页出来
        $this->assign("page", $result->currentPage());//当前页码

        // type类型:1初级岗位,2中级岗位,3高级岗位
        // status 状态:1未支付,2已报名,3已到岗,4已驳回,5已弃岗
        // 根据类型,和状态,算出每个岗位人数,以及入岗率

        // 定义岗位类型
        $jobTypes = $WorkInit->type;

        $statistics = [];

        foreach ($jobTypes as $type => $name) {
            // 计算总人数（状态为2,3,4,5）
            $totalCount = $WorkOrderModel->where([
                ['type', '=', $type],
                ['status', 'in', [2, 3, 4, 5]]
            ])->count();

            // 计算已到岗人数（状态为3）
            $onboardCount = $WorkOrderModel->where([
                ['type', '=', $type],
                ['status', '=', 3]
            ])->count();

            // 计算入岗率（避免除以零）
            $onboardRate = $totalCount > 0 ? round($onboardCount / $totalCount, 4) * 100 : 0;

            $statistics[$type] = [
                'name'          => $name,
                'total_count'   => $totalCount,//总人数
                'onboard_count' => $onboardCount,//已到岗人数
                'onboard_rate'  => $onboardRate//入岗率
            ];
        }

        // 分配统计数据到模板
        $this->assign("statistics", $statistics);


        return $this->fetch();
    }

    //添加
    public function add()
    {
        $this->base_edit();//处理基础信息

        return $this->fetch();
    }


    //添加提交
    public function add_post()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'WorkOrder');
        if ($validateResult !== true) $this->error($validateResult);


        /** 插入数据 **/
        $result = $WorkOrderInit->admin_edit_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //查看详情
    public function find()
    {
        $this->base_edit();//处理基础信息

        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位    (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //编辑详情
    public function edit()
    {
        $this->base_edit();//处理基础信息

        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位  (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //提交编辑
    public function edit_post()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'WorkOrder');
        if ($validateResult !== true) $this->error($validateResult);


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交数据 **/
        $result = $WorkOrderInit->admin_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //提交(副本,无任何操作) 编辑&添加
    public function edit_post_two()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        /** 提交数据 **/
        $result = $WorkOrderInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //驳回
    public function refuse()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位  (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }

    //驳回
    public function refuse2()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位  (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //驳回,更改状态
    public function audit_post()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $item                    = $WorkOrderInit->get_find($where);
        if (empty($item)) $this->error("暂无数据");

        /** 通过&拒绝时间 **/
        if ($params['status'] == 3) {
            $params['pass_time']    = time();
            $params['onboard_time'] = time();
        }
        if ($params['status'] == 4) $params['refuse_time'] = time();
        if ($params['status'] == 5) $params['reason_time'] = time();

        /** 提交数据 **/
        $result = $WorkOrderInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }

    //删除
    public function delete()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        if ($params["id"]) $id = $params["id"];
        if (empty($params["id"])) $id = $this->request->param("ids/a");

        /** 删除数据 **/
        $result = $WorkOrderInit->delete_post($id);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");//   , "index{$this->params_url}"
    }


    //批量操作
    public function batch_post()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param();

        $id = $this->request->param("id/a");
        if (empty($id)) $id = $this->request->param("ids/a");

        //提交编辑
        $result = $WorkOrderInit->batch_post($id, $params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功");//   , "index{$this->params_url}"
    }


    //更新排序
    public function list_order_post()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $params         = $this->request->param("list_order/a");

        //提交更新
        $result = $WorkOrderInit->list_order_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功"); //   , "index{$this->params_url}"
    }


}
