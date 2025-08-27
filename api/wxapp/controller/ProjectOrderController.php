<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ProjectOrder",
 *     "name_underline"          =>"project_order",
 *     "controller_name"         =>"ProjectOrder",
 *     "table_name"              =>"project_order",
 *     "remark"                  =>"项目报名"
 *     "api_url"                 =>"/api/wxapp/project_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-26 17:48:37",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ProjectOrderController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/project_order/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/project_order/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ProjectOrderController extends AuthController
{

    //public function initialize(){
    //	//项目报名
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/project_order/index
     * https://xcxkf161.aubye.com/api/wxapp/project_order/index
     */
    public function index()
    {
        $ProjectOrderInit  = new \init\ProjectOrderInit();//项目报名   (ps:InitController)
        $ProjectOrderModel = new \initmodel\ProjectOrderModel(); //项目报名   (ps:InitModel)

        $result = [];

        $this->success('项目报名-接口请求成功', $result);
    }


    /**
     * 项目报名 列表
     * @OA\Post(
     *     tags={"项目报名"},
     *     path="/wxapp/project_order/find_order_list",
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="状态:2已报名,8已完成,10已取消",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="(选填)关键字搜索",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="is_paginate",
     *         in="query",
     *         description="false=分页(不传默认分页),true=不分页",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/project_order/find_order_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project_order/find_order_list
     *   api:  /wxapp/project_order/find_order_list
     *   remark_name: 项目报名 列表
     *
     */
    public function find_order_list()
    {
        $this->checkAuth();

        $ProjectOrderInit  = new \init\ProjectOrderInit();//项目报名   (ps:InitController)
        $ProjectOrderModel = new \initmodel\ProjectOrderModel(); //项目报名   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['status', 'in', [2, 8, 10]];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["name|order_num", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ProjectOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ProjectOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 项目报名 详情
     * @OA\Post(
     *     tags={"项目报名"},
     *     path="/wxapp/project_order/find_order",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id 二选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="order_num",
     *         in="query",
     *         description="order_num 二选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/project_order/find_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project_order/find_order
     *   api:  /wxapp/project_order/find_order
     *   remark_name: 项目报名 详情
     *
     */
    public function find_order()
    {
        $this->checkAuth();
        $ProjectOrderInit  = new \init\ProjectOrderInit();//项目报名    (ps:InitController)
        $ProjectOrderModel = new \initmodel\ProjectOrderModel(); //项目报名   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ProjectOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"项目报名"},
     *     path="/wxapp/project_order/add_order",
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="project_id",
     *         in="query",
     *         description="项目id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/project_order/add_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project_order/add_order
     *   api:  /wxapp/project_order/add_order
     *   remark_name:  下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        $ProjectOrderInit  = new \init\ProjectOrderInit();//项目报名    (ps:InitController)
        $ProjectOrderModel = new \initmodel\ProjectOrderModel(); //项目报名   (ps:InitModel)
        $ProjectInit       = new \init\ProjectInit();//项目预定   (ps:InitController)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        $project_info = $ProjectInit->get_find($params['project_id']);
        if (empty($project_info)) $this->error("项目不存在");


        $order_num                   = $this->get_num_only();
        $insert['order_num']         = $order_num;
        $insert['user_id']           = $this->user_id;
        $insert['project_id']        = $params['project_id'];
        $insert['name']              = $project_info['name'];
        $insert['image']             = $project_info['image'];
        $insert['amount']            = $project_info['price'];
        $insert['part_refund']       = $project_info['part_refund'];
        $insert['whole_refund_time'] = $project_info['whole_refund_time'];
        $insert['part_refund_time']  = $project_info['part_refund_time'];
        $insert['create_time']       = time();


        /** 提交更新 **/
        $result = $ProjectOrderModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");


        $this->success('下单成功,请支付', ['order_num' => $order_num, 'order_type' => 100]);
    }


    /**
     * 取消订单
     * @OA\Post(
     *     tags={"项目报名"},
     *     path="/wxapp/project_order/cancel_order",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/project_order/cancel_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project_order/cancel_order
     *   api:  /wxapp/project_order/cancel_order
     *   remark_name: 取消订单
     *
     */
    public function cancel_order()
    {
        $this->checkAuth();
        $ProjectOrderInit  = new \init\ProjectOrderInit();//项目报名    (ps:InitController)
        $ProjectOrderModel = new \initmodel\ProjectOrderModel(); //项目报名   (ps:InitModel)
        $WxBaseController  = new WxBaseController();//微信基础类

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];


        //查看订单详情
        $order_info = $ProjectOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("订单错误");
        if ($order_info['status'] != 2) $this->error("订单状态错误");


        //判断是否要退钱
        if ($order_info['whole_refund_time'] > time()) {
            //全部退款
            $refund_result = $WxBaseController->wx_refund($order_info['pay_num'], $order_info['amount']);//退款测试&输入单号直接退
            if ($refund_result['code'] == 0) $this->error($refund_result['msg']);
        } elseif ($order_info['part_refund_time'] > time()) {
            //部分退款
            $refund_fee    = $order_info['amount'] * ($order_info['part_refund'] / 100);
            $refund_result = $WxBaseController->wx_refund($order_info['pay_num'], $refund_fee, $order_info['amount']);//退款测试&输入单号直接退
            if ($refund_result['code'] == 0) $this->error($refund_result['msg']);
        }


        //更新订单状态
        $result = $ProjectOrderModel->where($where)->strict(false)->update([
            'status'      => 10,
            'cancel_time' => time(),
        ]);
        if (empty($result)) $this->error("暂无数据");


        $this->success("操作成功");
    }


}
