<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"MemberVip",
 *     "name_underline"          =>"member_vip",
 *     "controller_name"         =>"MemberVip",
 *     "table_name"              =>"member_vip",
 *     "remark"                  =>"会员管理"
 *     "api_url"                 =>"/api/wxapp/member_vip/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-26 15:51:29",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\MemberVipController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/member_vip/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/member_vip/index",
 * )
 */


use initmodel\AssetModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class MemberVipController extends AuthController
{

    //public function initialize(){
    //	//会员管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/member_vip/index
     * https://xcxkf161.aubye.com/api/wxapp/member_vip/index
     */
    public function index()
    {
        $MemberVipInit  = new \init\MemberVipInit();//会员管理   (ps:InitController)
        $MemberVipModel = new \initmodel\MemberVipModel(); //会员管理   (ps:InitModel)

        $result = [];

        $this->success('会员管理-接口请求成功', $result);
    }


    /**
     * 会员管理 列表
     * @OA\Post(
     *     tags={"会员管理"},
     *     path="/wxapp/member_vip/find_vip_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/member_vip/find_vip_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member_vip/find_vip_list
     *   api:  /wxapp/member_vip/find_vip_list
     *   remark_name: 会员管理 列表
     *
     */
    public function find_vip_list()
    {
        $MemberVipInit  = new \init\MemberVipInit();//会员管理   (ps:InitController)
        $MemberVipModel = new \initmodel\MemberVipModel(); //会员管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];

        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $MemberVipInit->get_list($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 获取价格
     * @OA\Post(
     *     tags={"会员管理"},
     *     path="/wxapp/member_vip/get_amount",
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
     *    @OA\Parameter(
     *         name="vip_id",
     *         in="query",
     *         description="会员id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="is_point",
     *         in="query",
     *         description="true 使用积分抵扣",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/member_vip/get_amount
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member_vip/get_amount
     *   api:  /wxapp/member_vip/get_amount
     *   remark_name: 获取价格
     *
     */
    public function get_amount()
    {
        $this->checkAuth();

        $MemberVipOrderModel = new \initmodel\MemberVipOrderModel();
        $MemberVipModel      = new \initmodel\MemberVipModel();

        $params = $this->request->param();

        // 验证参数
        if (empty($params['vip_id'])) {
            $this->error("会员ID不能为空!");
        }

        // 查询会员信息
        $vip_info = $MemberVipModel->where('id', '=', $params['vip_id'])->find();
        if (empty($vip_info)) {
            $this->error("会员不存在!");
        }


        $open_vip_point = cmf_config('open_vip_point'); // 获取配置：开通会员时人民币换算积分比例(%)
        $point_per_yuan = $open_vip_point / 100; // 1元对应的积分

        $user_point = $this->user_info['point'] ?? 0; // 用户当前积分
        $vip_price  = $vip_info['price']; // 会员原价

        $point               = 0;    // 实际使用积分
        $amount              = 0;   // 实际支付金额
        $point_deduct_amount = 0; // 积分抵扣金额

        // 使用积分抵扣
        if (!empty($params['is_point'])) {
            // 计算需要的总积分
            $required_point = $vip_price * $point_per_yuan;

            if ($user_point >= $required_point) {
                // 积分足够，全额抵扣
                $point               = $required_point;
                $amount              = 0;
                $point_deduct_amount = $vip_price;
            } else {
                // 积分不足，部分抵扣
                $point               = $user_point;
                $point_deduct_amount = $point / $point_per_yuan; // 积分抵扣的金额
                $amount              = $vip_price - $point_deduct_amount;
            }
        } else {
            // 不使用积分抵扣
            $amount = $vip_price;
        }

        $result = [
            'amount'              => round($amount, 2), // 现金（保留2位小数）
            'point'               => $point, // 积分
            'original_price'      => $vip_price, // 原价
            'point_deduct_amount' => round($point_deduct_amount, 2), // 积分抵扣金额
            'user_point'          => $user_point, // 用户当前积分
        ];

        $this->success('计算成功', $result);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"会员管理"},
     *     path="/wxapp/member_vip/add_order",
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
     *    @OA\Parameter(
     *         name="vip_id",
     *         in="query",
     *         description="会员id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="is_point",
     *         in="query",
     *         description="true 使用积分抵扣",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/member_vip/add_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member_vip/add_order
     *   api:  /wxapp/member_vip/add_order
     *   remark_name: 下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        // 启动事务
        Db::startTrans();

        $MemberVipOrderModel = new \initmodel\MemberVipOrderModel(); //订单管理   (ps:InitModel)
        $MemberVipModel      = new \initmodel\MemberVipModel(); //会员管理   (ps:InitModel)
        $NotifyController    = new NotifyController();
        $OrderPayModel       = new \initmodel\OrderPayModel();


        $params = $this->request->param();

        //查询会员信息
        $vip_info = $MemberVipModel->where('id', '=', $params['vip_id'])->find();
        if (empty($vip_info)) $this->error("会员不存在!");


        //获取订单号
        $order_num = $this->get_num_only('order_num', 8, 1, null, 'member_vip_order');


        $open_vip_point = cmf_config('open_vip_point'); // 获取配置：开通会员时人民币换算积分比例(%)
        $point_per_yuan = $open_vip_point / 100; // 1元对应的积分

        $user_point = $this->user_info['point'] ?? 0; // 用户当前积分
        $vip_price  = $vip_info['price']; // 会员原价

        $point               = 0;    // 实际使用积分
        $amount              = 0;   // 实际支付金额
        $point_deduct_amount = 0; // 积分抵扣金额

        // 使用积分抵扣
        if (!empty($params['is_point'])) {
            // 计算需要的总积分
            $required_point = $vip_price * $point_per_yuan;

            if ($user_point >= $required_point) {
                // 积分足够，全额抵扣
                $point               = $required_point;
                $amount              = 0;
                $point_deduct_amount = $vip_price;
            } else {
                // 积分不足，部分抵扣
                $point               = $user_point;
                $point_deduct_amount = $point / $point_per_yuan; // 积分抵扣的金额
                $amount              = $vip_price - $point_deduct_amount;
            }
        } else {
            // 不使用积分抵扣
            $amount = $vip_price;
        }


        //会员到期时间
        if ($this->user_info['vip_end_time'] > time()) {
            $vip_end_time = $this->user_info['vip_end_time'] + ($vip_info['day'] * 86400);
        } else {
            $vip_end_time = time() + ($vip_info['day'] * 86400);
        }

        /** 插入数据 **/
        $insert['user_id']             = $this->user_id;
        $insert['order_num']           = $order_num;
        $insert['openid']              = $this->openid;
        $insert['wx_openid']           = $this->user_info['wx_openid'];
        $insert['user_phone']          = $this->user_info['phone'];
        $insert['point']               = $point;
        $insert['amount']              = $amount;
        $insert['point_deduct_amount'] = $point_deduct_amount;
        $insert['original_price']      = $vip_price;
        $insert['vip_name']            = $vip_info['name'];
        $insert['vip_id']              = $vip_info['id'];
        $insert['vip_end_time']        = $vip_end_time;
        $insert['create_time']         = time();

        $result = $MemberVipOrderModel->strict(false)->insert($insert);
        if (empty($result)) $this->error("失败请重试");

        //不用支付
        if ($amount <= 0 && $params['is_point']) {
            //扣除积分
            $remark = "操作人[开通会员使用积分抵扣];操作说明[开通会员使用积分抵扣];操作类型[开通会员使用积分抵扣];";//管理备注
            AssetModel::decAsset('下单得积分,给上级发放佣金 [250]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $this->user_id,
                'price'         => $point,
                'order_num'     => $order_num,
                'order_type'    => 250,
                'content'       => '开通会员抵扣',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);

            //支付记录插入一条记录
            $pay_num = $OrderPayModel->add($this->openid, $order_num, $amount, 250, 2);
            //积分 支付回调
            $NotifyController->pointPayNotify($pay_num);
        }


        // 提交事务
        Db::commit();


        $this->success('下单成功,请支付!', ['order_num' => $order_num, 'order_type' => 250]);
    }


}
