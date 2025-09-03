<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"WorkOrder",
 *     "name_underline"          =>"work_order",
 *     "controller_name"         =>"WorkOrder",
 *     "table_name"              =>"work_order",
 *     "remark"                  =>"报名岗位"
 *     "api_url"                 =>"/api/wxapp/work_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-27 15:12:05",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\WorkOrderController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/work_order/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/work_order/index",
 * )
 */


use initmodel\AssetModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class WorkOrderController extends AuthController
{

    //public function initialize(){
    //	//报名岗位
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/work_order/index
     * https://xcxkf161.aubye.com/api/wxapp/work_order/index
     */
    public function index()
    {
        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)

        $result = [];

        $this->success('报名岗位-接口请求成功', $result);
    }


    /**
     * 报名岗位 列表
     * @OA\Post(
     *     tags={"报名岗位"},
     *     path="/wxapp/work_order/find_order_list",
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
     *         description="状态:2已报名,3已到岗,4已驳回,5已弃岗,10已取消",
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
     *         description="类型:1初级岗位,2中级岗位,3高级岗位",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work_order/find_order_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work_order/find_order_list
     *   api:  /wxapp/work_order/find_order_list
     *   remark_name: 报名岗位 列表
     *
     */
    public function find_order_list()
    {
        $this->checkAuth();

        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位   (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['user_id', '=', $this->user_id];
        $where[] = ['status', 'in', [2, 3, 4, 5, 10]];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $WorkOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $WorkOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 报名岗位 详情
     * @OA\Post(
     *     tags={"报名岗位"},
     *     path="/wxapp/work_order/find_order",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work_order/find_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work_order/find_order
     *   api:  /wxapp/work_order/find_order
     *   remark_name: 报名岗位 详情
     *
     */
    public function find_order()
    {
        $this->checkAuth();

        $WorkOrderInit  = new \init\WorkOrderInit();//报名岗位    (ps:InitController)
        $WorkOrderModel = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["user_id", "=", $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"报名岗位"},
     *     path="/wxapp/work_order/add_order",
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
     *         name="work_id",
     *         in="query",
     *         description="work_id",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work_order/add_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work_order/add_order
     *   api:  /wxapp/work_order/add_order
     *   remark_name: 下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        $WorkOrderInit    = new \init\WorkOrderInit();//报名岗位    (ps:InitController)
        $WorkOrderModel   = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $WorkModel        = new \initmodel\WorkModel(); //岗位管理   (ps:InitModel)
        $NotifyController = new NotifyController();
        $OrderPayModel    = new \initmodel\OrderPayModel();

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;


        $map       = [];
        $map[]     = ['id', '=', $params['work_id']];
        $work_info = $WorkModel->where($map)->find();
        if (empty($work_info)) $this->error("岗位不存在");


        //下单后n小时后不退款
        $refund_after_hours = cmf_config('refund_after_hours');


        $order_num                 = $this->get_num_only();
        $insert['order_num']       = $order_num;
        $insert['user_id']         = $this->user_id;
        $insert['work_id']         = $params['work_id'];
        $insert['type']            = $work_info['type'];
        $insert['p_type']          = $work_info['p_type'];//支付方式:1现金,2积分
        $insert['name']            = $work_info['name'];
        $insert['company_name']    = $work_info['company_name'];
        $insert['amount']          = $work_info['price'];
        $insert['salary']          = $work_info['salary'];
        $insert['tag']             = $work_info['tag'];
        $insert['create_time']     = time();
        $insert['max_refund_time'] = time() + ($refund_after_hours * 3600);


        /** 插入数据 **/
        $result = $WorkOrderModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");


        //积分抵扣
        if ($work_info['p_type'] == 2) {
            if ($this->user_info['point'] < $work_info['price']) $this->error("积分不足");
            //扣除积分
            $remark = "操作人[岗位报名使用积分抵扣];操作说明[岗位报名使用积分抵扣];操作类型[岗位报名使用积分抵扣];";//管理备注
            AssetModel::decAsset('下单得积分,给上级发放佣金 [260]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $this->user_id,
                'price'         => $insert['amount'],
                'order_num'     => $order_num,
                'order_type'    => 260,
                'content'       => '岗位报名',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);

            //支付记录插入一条记录
            $pay_num = $OrderPayModel->add($this->openid, $order_num, $insert['amount'], 260, 2);
            //积分 支付回调
            $NotifyController->pointPayNotify($pay_num);

            //修改订单,支付类型 ,积分支付
            $WorkOrderModel->where('order_num', '=', $order_num)->strict(false)->update([
                'pay_type'    => 3,
                'update_time' => time(),
            ]);


            $this->success('支付成功');
        }


        $this->success('下单成功,请支付!', ['order_num' => $order_num, 'order_type' => 260]);
    }


    /**
     * 取消报名
     * @OA\Post(
     *     tags={"报名岗位"},
     *     path="/wxapp/work_order/cancel_order",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work_order/cancel_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work_order/cancel_order
     *   api:  /wxapp/work_order/cancel_order
     *   remark_name: 取消报名
     *
     */
    public function cancel_order()
    {
        $this->checkAuth();
        $WorkOrderModel   = new \initmodel\WorkOrderModel(); //报名岗位   (ps:InitModel)
        $WxBaseController = new WxBaseController();//微信基础类

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];


        //查看订单详情
        $order_info = $WorkOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("订单错误");
        if ($order_info['status'] != 2) $this->error("订单状态错误");


        //判断是否要退钱
        if ($order_info['max_refund_time'] > time()) {

            //微信支付
            if ($order_info['pay_type'] == 1) {
                //全部退款
                $refund_result = $WxBaseController->wx_refund($order_info['pay_num'], $order_info['amount']);//退款测试&输入单号直接退
                if ($refund_result['code'] == 0) $this->error($refund_result['msg']);
            }

            //积分支付
            if ($order_info['pay_type'] == 3) {
                //全部退款
                $remark = "操作人[岗位报名取消积分退回];操作说明[岗位报名取消积分退回];操作类型[岗位报名取消积分退回];";//管理备注
                AssetModel::incAsset('下单得积分,给上级发放佣金 [270]', [
                    'operate_type'  => 'point',//操作类型，balance|point ...
                    'identity_type' => 'member',//身份类型，member| ...
                    'user_id'       => $order_info['user_id'],
                    'price'         => $order_info['amount'],
                    'order_num'     => $order_info['order_num'],
                    'order_type'    => 270,
                    'content'       => '岗位报名取消',
                    'remark'        => $remark,
                    'order_id'      => 0,
                ]);
            }

        }


        //更新订单状态
        $result = $WorkOrderModel->where($where)->strict(false)->update([
            'status'      => 10,
            'cancel_time' => time(),
        ]);
        if (empty($result)) $this->error("暂无数据");


        $this->success("操作成功");
    }


}
