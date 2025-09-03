<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ActivityOrder",
 *     "name_underline"          =>"activity_order",
 *     "controller_name"         =>"ActivityOrder",
 *     "table_name"              =>"activity_order",
 *     "remark"                  =>"活动报名"
 *     "api_url"                 =>"/api/wxapp/activity_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-28 10:38:40",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityOrderController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/activity_order/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/activity_order/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ActivityOrderController extends AuthController
{

    //public function initialize(){
    //	//活动报名
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity_order/index
     * https://xcxkf161.aubye.com/api/wxapp/activity_order/index
     */
    public function index()
    {
        $ActivityOrderInit  = new \init\ActivityOrderInit();//活动报名   (ps:InitController)
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        $result = [];

        $this->success('活动报名-接口请求成功', $result);
    }


    /**
     * 活动报名 列表
     * @OA\Post(
     *     tags={"活动报名"},
     *     path="/wxapp/activity_order/find_order_list",
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
     *         description="状态:1待支付,2已报名,3进行中,8已完成,10已取消",
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
     *         name="type",
     *         in="query",
     *         description="类型:1线上活动,2线下活动",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity_order/find_order_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity_order/find_order_list
     *   api:  /wxapp/activity_order/find_order_list
     *   remark_name: 活动报名 列表
     *
     */
    public function find_order_list()
    {
        $this->checkAuth();

        $ActivityOrderInit  = new \init\ActivityOrderInit();//活动报名   (ps:InitController)
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['user_id', '=', $this->user_id];
        $where[] = ["status", "in", [2, 3, 8, 10]];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ActivityOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ActivityOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 活动报名 详情
     * @OA\Post(
     *     tags={"活动报名"},
     *     path="/wxapp/activity_order/find_order",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity_order/find_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity_order/find_order
     *   api:  /wxapp/activity_order/find_order
     *   remark_name: 活动报名 详情
     *
     */
    public function find_order()
    {
        $this->checkAuth();
        $ActivityOrderInit  = new \init\ActivityOrderInit();//活动报名    (ps:InitController)
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ['order_num', '=', $params['order_num']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 活动报名 下单
     * @OA\Post(
     *     tags={"活动报名"},
     *     path="/wxapp/activity_order/add_order",
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
     *         name="activity_id",
     *         in="query",
     *         description="活动id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="pid",
     *         in="query",
     *         description="上级用户id",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity_order/add_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity_order/add_order
     *   api:  /wxapp/activity_order/add_order
     *   remark_name: 活动报名 下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        $ActivityOrderInit  = new \init\ActivityOrderInit();//活动报名    (ps:InitController)
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)
        $ActivityModel      = new \initmodel\ActivityModel(); //赛事活动   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        //赛事活动详情
        $activity_info = $ActivityModel->where('id', '=', $params['activity_id'])->find();
        if (empty($activity_info)) $this->error("活动不存在");
        if ($activity_info['status'] != 1) $this->error("活动已结束");


        //查看报名人数是否已满
        $map          = [];
        $map[]        = ['activity_id', '=', $params['activity_id']];
        $map[]        = ['status', 'in', [2, 3, 8]];
        $enroll_count = $ActivityOrderModel->where($map)->lock(true)->count();
        if ($enroll_count >= $activity_info['number']) $this->error("报名人数已满");

        //取消订单扣除报名费的(%)
        $activity_cancel_order = cmf_config('activity_cancel_order');


        /** 提交 **/
        $order_num              = $this->get_num_only();
        $insert['order_num']    = $order_num;
        $insert['user_id']      = $this->user_id;
        $insert['pid']          = $params['pid'] ?? 0;
        $insert['activity_id']  = $params['activity_id'];
        $insert['type']         = $activity_info['type'];
        $insert['name']         = $activity_info['name'];
        $insert['amount']       = $activity_info['price'];
        $insert['image']        = cmf_get_asset_url($activity_info['image']);
        $insert['base_point']   = $activity_info['base_point'];
        $insert['reward_point'] = $activity_info['reward_point'];
        $insert['invite_point'] = $activity_info['invite_point'];
        $insert['begin_time']   = $activity_info['begin_time'];
        $insert['end_time']     = $activity_info['end_time'];
        $insert['time']         = $activity_info['time'];
        $insert['part_refund']  = $activity_cancel_order;
        $insert['early_point']  = 0;
        $insert['enroll_time']  = time();
        $insert['create_time']  = time();


        //判断是否在早鸟积分
        if ($activity_info['is_early'] == 1) {
            $activity_info['early_time'] = $activity_info['early_time'] ?? '00:00';
            $early_time                  = date("Y-m-d {$activity_info['early_time']}", $activity_info['end_time'] - ($activity_info['early_day'] * 24 * 60 * 60));
            if (time() < strtotime($early_time)) $insert['early_point'] = $activity_info['early_point'];
        }


        //报名后超过n小时后,取消订单扣报名费
        $activity_refund_time       = cmf_config('activity_refund_time');
        $insert['part_refund_time'] = time() + ($activity_refund_time * 60 * 60);


        $result = $ActivityOrderModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");


        $this->success('报名成功,请支付', ['order_num' => $order_num, 'order_type' => 50]);
    }


    /**
     * 取消订单
     * @OA\Post(
     *     tags={"活动报名"},
     *     path="/wxapp/activity_order/cancel_order",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity_order/cancel_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity_order/cancel_order
     *   api:  /wxapp/activity_order/cancel_order
     *   remark_name: 取消订单
     *
     */
    public function cancel_order()
    {
        $this->checkAuth();
        $ActivityOrderInit  = new \init\ActivityOrderInit();//活动报名    (ps:InitController)
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)
        $WxBaseController   = new WxBaseController();//微信基础类
        $InitController     = new InitController();

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ['order_num', '=', $params['order_num']];


        /** 查询数据 **/
        $order_info = $ActivityOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("暂无此订单");
        if (in_array($order_info["status"], [1, 8, 10])) $this->error("状态错误");


        //检测是否可以退钱
        if (time() > $order_info['part_refund_time']) {
            //部分退款
            $activity_cancel_order = cmf_config('activity_cancel_order');   //取消订单扣除报名费的(%)
            $refund_amount         = round($order_info['amount'] - ($order_info['amount'] * $activity_cancel_order / 100), 2);
            $refund_result         = $WxBaseController->wx_refund($order_info['pay_num'], $refund_amount, $order_info['amount']);//退款测试&输入单号直接退
            if ($refund_result['code'] == 0) $this->error($refund_result['msg']);
        } elseif ($order_info['part_refund_time'] > time()) {
            //全部退款
            $refund_amount = $order_info['amount'];
            $refund_result = $WxBaseController->wx_refund($order_info['pay_num'], $order_info['amount']);//退款测试&输入单号直接退
            if ($refund_result['code'] == 0) $this->error($refund_result['msg']);
        }

        //将关联订单的冻结金额全部扣除
        $InitController->cancelActivity($order_info['order_num']);


        //更新订单状态
        $result = $ActivityOrderModel->where($where)->strict(false)->update([
            'status'        => 10,
            'refund_amount' => $refund_amount,
            'cancel_time'   => time(),
            'update_time'   => time(),
        ]);

        if (empty($result)) $this->error("暂无数据");

        $this->success("操作成功");
    }


}
