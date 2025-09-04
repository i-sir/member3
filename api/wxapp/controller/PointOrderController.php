<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"PointOrder",
 *     "name_underline"          =>"point_order",
 *     "controller_name"         =>"PointOrder",
 *     "table_name"              =>"point_order",
 *     "remark"                  =>"订单管理"
 *     "api_url"                 =>"/api/wxapp/point_order/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-27 16:44:05",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\PointOrderController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/point_order/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/point_order/index",
 * )
 */


use init\QrInit;
use initmodel\AssetModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class PointOrderController extends AuthController
{

    //public function initialize(){
    //	//订单管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/point_order/index
     * https://xcxkf161.aubye.com/api/wxapp/point_order/index
     */
    public function index()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        $result = [];

        $this->success('订单管理-接口请求成功', $result);
    }


    /**
     * 订单管理 列表
     * @OA\Post(
     *     tags={"订单管理"},
     *     path="/wxapp/point_order/find_order_list",
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
     *
     *    @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="状态:2待核销,8已核销",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="is_admin",
     *         in="query",
     *         description="true  核销记录",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_order/find_order_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_order/find_order_list
     *   api:  /wxapp/point_order/find_order_list
     *   remark_name: 订单管理 列表
     *
     */
    public function find_order_list()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['status', 'in', [2, 4, 8]];
        $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["order_num|cav_code", "like", "%{$params['keyword']}%"];
        if ($params["type"]) $where[] = ["type", "=", $params["type"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $PointOrderInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $PointOrderInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 订单管理 详情
     * @OA\Post(
     *     tags={"订单管理"},
     *     path="/wxapp/point_order/find_order",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="三选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="order_num",
     *         in="query",
     *         description="三选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="cav_code",
     *         in="query",
     *         description="核销码  三选一",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_order/find_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_order/find_order
     *   api:  /wxapp/point_order/find_order
     *   remark_name: 订单管理 详情
     *
     */
    public function find_order()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $PointOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 计算价格
     * @OA\Post(
     *     tags={"订单管理"},
     *     path="/wxapp/point_order/get_amount",
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
     *         name="address_id",
     *         in="query",
     *         description="地址id ",
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
     *         name="goods_id",
     *         in="query",
     *         description="单独下单",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="sku_id",
     *         in="query",
     *         description="单独下单",
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
     *         name="count",
     *         in="query",
     *         description="数量  单独下单",
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
     *         name="is_all_point",
     *         in="query",
     *         description="true 使用所有积分",
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
     *   test_environment: http://shop_template.ikun:9090/api/wxapp/point_order/get_amount
     *   official_environment: http://shop_template.com/api/wxapp/point_order/get_amount
     *   api: /wxapp/point_order/get_amount
     *   remark_name: 计算价格
     *
     */
    public function get_amount()
    {
        $this->checkAuth();
        $params          = $this->request->param();
        $PointGoodsModel = new \initmodel\PointGoodsModel(); //商品管理

        $count = (empty($params['count']) ? 1 : $params['count']) ?? 1;

        $amount         = 0; //实际支付金额
        $goods_amount   = 0; //商品金额
        $min_amount     = 0; //最低积分
        $coupon_amount  = 0; //优惠金额
        $freight_amount = 0; //运费
        $point          = 0; //积分支付
        $original_price = 0; //原价
        $total_amount   = 0; //订单总金额
        $type           = ''; //商品类型
        $goods_list     = []; //商品信息

        /** 单独购买 */
        //商品信息
        $goods_info = $PointGoodsModel->where('id', '=', $params['goods_id'])->find();
        if (empty($goods_info)) $this->error("商品不存在");


        //商品金额计算
        $goods_amount = round($goods_info['price'] * $count, 2);
        $min_amount   = round($goods_info['min_price'] * $count, 2);

        //订单总金额（商品金额+运费金额）
        $total_amount = $goods_amount + $freight_amount;

        //商品信息返回
        $goods_list[] = [
            'goods_id'   => $goods_info['id'],
            'sku_name'   => $params['sku_name'] ?? '',
            'goods_name' => $goods_info['goods_name'],
            'price'      => $goods_info['price'],
            'line_price' => $goods_info['line_price'],
            'count'      => $count,
            'image'      => cmf_get_image_url($goods_info['image']),
        ];

        //商品类型
        $type = $goods_info['type'];

        //兑换商品时,金额换算积分比例(%)
        $goods_conversion_points = cmf_config('goods_conversion_points'); //目前值为100

        //计算积分需要多少,现金需要多少
        $user_point = $this->user_info['point'];

        /**
         * 积分计算逻辑：
         * 1. 计算商品总共需要的积分 = 商品金额 * 积分比例
         * 2. 用户可用积分 = min(用户当前积分, 商品总共需要的积分)
         * 3. 积分可抵扣的金额 = 用户可用积分 / 积分比例
         * 4. 实际需要支付的现金 = 商品金额 - 积分可抵扣的金额
         */

        //计算商品总共需要的积分
        $total_points_needed = $goods_amount * ($goods_conversion_points / 100);

        //计算实际可用积分（不能超过用户拥有的积分） ,默认使用商品设定最低积分
        $usable_points = min($min_amount, $total_points_needed);
        if ($params['is_all_point']) $usable_points = min($user_point, $total_points_needed);

        //计算积分抵扣的金额
        $point_discount_amount = $usable_points / ($goods_conversion_points / 100);


        //计算实际需要支付的现金金额
        $amount = max(0, $goods_amount - $point_discount_amount);

        //设置积分支付数量
        $point = $usable_points;


        //价格信息
        $order_insert['coupon_amount']       = round($coupon_amount, 2);//优惠金额
        $order_insert['amount']              = round($amount, 2);//实际支付金额
        $order_insert['point']               = round($point, 2);//需要多少积分
        $order_insert['goods_amount']        = round($goods_amount, 2);//商品金额
        $order_insert['min_point']           = round($min_amount, 2);//最低积分值
        $order_insert['total_amount']        = round($total_amount, 2);//订单总金额,实际支付金额+优惠金额+运费金额+会员折扣金额
        $order_insert['original_price']      = round($original_price, 2);//原价
        $order_insert['goods_list']          = $goods_list;//商品信息
        $order_insert['type']                = $type;//商品类型
        $order_insert['point_ratio']         = $goods_conversion_points;//积分比例（用于前端显示）
        $order_insert['user_point']          = $user_point;//用户当前积分
        $order_insert['total_points_needed'] = round($total_points_needed, 2);//总共需要的积分


        $this->success('计算成功', $order_insert);
    }


    /**
     * 下单
     * @OA\Post(
     *     tags={"订单管理"},
     *     path="/wxapp/point_order/add_order",
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
     *         name="address_id",
     *         in="query",
     *         description="地址id ",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="remark",
     *         in="query",
     *         description="备注",
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
     *         name="cart_ids",
     *         in="query",
     *         description="购物车下单 (数组[1,2,3])",
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
     *         name="goods_id",
     *         in="query",
     *         description="单独下单",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="sku_id",
     *         in="query",
     *         description="单独下单",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *    @OA\Parameter(
     *         name="sku_name",
     *         in="query",
     *         description="单独下单",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="count",
     *         in="query",
     *         description="数量  单独下单",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="coupon_id",
     *         in="query",
     *         description="优惠券id (选填)",
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
     *         name="type",
     *         in="query",
     *         description="商品类型:goods=普通商品,  (选填)如不穿默认普通商品",
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
     *   test_environment: http://shop_template.ikun:9090/api/wxapp/point_order/add_order
     *   official_environment: http://shop_template.com/api/wxapp/point_order/add_order
     *   api: /wxapp/point_order/add_order
     *   remark_name: 下单
     *
     */
    public function add_order()
    {
        $this->checkAuth();

        // 启动事务
        Db::startTrans();

        $params                = $this->request->param();
        $ShopAddressModel      = new \initmodel\ShopAddressModel(); //地址管理
        $PointGoodsModel       = new \initmodel\PointGoodsModel(); //商品管理
        $PointOrderModel       = new \initmodel\PointOrderModel(); //订单管理
        $PointOrderDetailModel = new \initmodel\PointOrderDetailModel(); //订单详情
        $NotifyController      = new NotifyController();
        $OrderPayModel         = new \initmodel\OrderPayModel();


        //订单基础信息
        $order_num                   = $this->get_num_only();
        $order_insert['user_id']     = $this->user_id;
        $order_insert['openid']      = $this->openid;
        $order_insert['wx_openid']   = $this->user_info['openid'];
        $order_insert['order_num']   = $order_num;
        $order_insert['user_phone']  = $this->user_info['phone'];
        $order_insert['p_user_id']   = $this->user_info['pid'];
        $order_insert['type']        = $params['type'] ?? 'goods';
        $order_insert['coupon_id']   = $params['coupon_id'] ?? 0;
        $order_insert['create_time'] = time();
        $count                       = (empty($params['count']) ? 1 : $params['count']) ?? 1;

        //地址信息
        $address_info = $ShopAddressModel->where('id', '=', $params['address_id'])->find();
        if (empty($address_info)) $this->error('地址信息错误');

        $order_insert['username'] = $address_info['username'];
        $order_insert['phone']    = $address_info['phone'];
        $order_insert['address']  = $address_info['address'];
        $order_insert['province'] = $address_info['province'];
        $order_insert['city']     = $address_info['city'];
        $order_insert['county']   = $address_info['county'];

        //订单自动取消时间
        $automatic_cancellation_order     = cmf_config('order_automatic_cancellation_time');
        $order_insert['auto_cancel_time'] = time() + ($automatic_cancellation_order * 60);

        $goods_name     = '';
        $amount         = 0; //实际支付金额
        $goods_amount   = 0; //商品金额
        $min_amount     = 0; //最低积分
        $coupon_amount  = 0; //优惠金额
        $freight_amount = 0; //运费
        $point          = 0; //积分支付
        $total_amount   = 0; //订单总金额
        $type           = ''; //商品类型

        //商品信息
        $goods_info = $PointGoodsModel->where('id', '=', $params['goods_id'])->find();
        if (empty($goods_info)) $this->error('商品信息错误');


        //检查库存
        if ($goods_info['stock'] < $count) $this->error('库存不足请重试!');


        //获取商品价格
        $sku_info['id']         = 0;
        $sku_info['price']      = $goods_info['price'];
        $sku_info['min_price']  = $goods_info['min_price'] ?? 0;
        $sku_info['goods_id']   = $goods_info['id'];
        $sku_info['image']      = $goods_info['image'];
        $sku_info['line_price'] = $goods_info['line_price']; //划线价

        //商品金额计算
        $goods_amount = round($goods_info['price'] * $count, 2);
        $min_amount   = round(($goods_info['min_price'] ?? 0) * $count, 2);

        //订单总金额（商品金额+运费金额）
        $total_amount = $goods_amount + $freight_amount;

        //商品类型
        $type = $goods_info['type'];

        //兑换商品时,金额换算积分比例(%)
        $goods_conversion_points = cmf_config('goods_conversion_points'); //目前值为100

        //计算积分需要多少,现金需要多少
        $user_point = $this->user_info['point'];

        //计算商品总共需要的积分
        $total_points_needed = $goods_amount * ($goods_conversion_points / 100);

        //计算实际可用积分（不能超过用户拥有的积分） ,默认使用商品设定最低积分
        $usable_points = min($min_amount, $total_points_needed);
        if ($params['is_all_point']) $usable_points = min($user_point, $total_points_needed);


        //计算积分抵扣的金额
        $point_discount_amount = $usable_points / ($goods_conversion_points / 100);

        //计算实际需要支付的现金金额
        $amount = max(0, $goods_amount - $point_discount_amount);

        //设置积分支付数量
        $point = $usable_points;

        //检查积分是否足够
        if ($user_point < $point) $this->error('积分不足，无法完成支付');


        //订单详情
        $order_detail['user_id']      = $this->user_id;
        $order_detail['goods_id']     = $goods_info['id'];
        $order_detail['goods_name']   = $goods_info['goods_name'];
        $order_detail['sku_id']       = $params['sku_id'] ?? 0;
        $order_detail['sku_name']     = $params['sku_name'] ?? '';
        $order_detail['count']        = $count;
        $order_detail['goods_price']  = $sku_info['price']; //单价
        $order_detail['min_price']    = $sku_info['min_price']; //最低积分
        $order_detail['line_price']   = $sku_info['line_price']; //划线价
        $order_detail['stock']        = $goods_info['stock'] - $count; //剩余库存
        $order_detail['sku_code']     = $sku_info['code'] ?? ''; //编码
        $order_detail['total_amount'] = round($sku_info['price'] * $count, 2); //合计
        $order_detail['order_num']    = $order_num;
        $order_detail['create_time']  = time();
        $order_detail['shop_id']      = $params['shop_id'] ?? $goods_info['shop_id'];
        $order_detail['image']        = cmf_get_asset_url($goods_info['image']);
        $order_detail['point_used']   = $point; //使用的积分数量
        $order_detail['point_ratio']  = $goods_conversion_points; //积分比例

        //商品名字
        $goods_name = $goods_info['goods_name'];

        //用于模糊查询
        $order_insert['goods_name'] = $goods_name;

        //价格信息
        $order_insert['count']          = $count; //下单数量
        $order_insert['amount']         = round($amount, 2); //实际支付金额
        $order_insert['freight_amount'] = round($freight_amount, 2); //运费
        $order_insert['goods_amount']   = round($goods_amount, 2); //商品金额
        $order_insert['min_amount']     = round($min_amount, 2); //最低积分值
        $order_insert['coupon_amount']  = round($coupon_amount, 2); //优惠金额
        $order_insert['total_amount']   = round($total_amount, 2); //订单总金额
        $order_insert['point']          = round($point, 2); //使用的积分数量
        $order_insert['point_ratio']    = $goods_conversion_points; //积分比例
        $order_insert['type']           = $type; //商品类型

        //处理每个商品,最多退款金额
        $order_detail = $this->setMaxRefundAmount([$order_detail], $coupon_amount)[0];

        //插入订单
        $order_id = $PointOrderModel->strict(false)->insertGetId($order_insert);
        if (empty($order_id)) $this->error('订单创建失败');


        //插入订单详情
        $detail_result = $PointOrderDetailModel->strict(false)->insert($order_detail);
        if (empty($detail_result)) $this->error('订单详情创建失败');


        if ($order_insert['amount'] <= 0) {

            //支付记录插入一条记录
            $pay_num = $OrderPayModel->add($this->openid, $order_num, $order_insert['amount'], 10, 2);
            //积分 支付回调
            $NotifyController->pointPayNotify($pay_num);

            //修改订单,支付类型 ,积分支付
            $PointOrderModel->where('order_num', '=', $order_num)->strict(false)->update([
                'pay_type'    => 3,
                'update_time' => time(),
            ]);

            // 提交事务
            Db::commit();


            $this->success('支付成功');
        }

        // 提交事务
        Db::commit();


        $this->success('下单成功', ['order_num' => $order_num, 'order_type' => 10]);
    }


    /**
     * 设置最多退款金额
     * @param array $order_detail  订单详情数组
     * @param float $coupon_amount 优惠金额
     * @param int   $type          1优惠金额 2满减金额
     * @return array 处理后的订单详情
     */
    public function setMaxRefundAmount($order_detail = [], $coupon_amount = 0, $type = 1)
    {
        // 如果没有优惠券信息，则每个商品的最大退款金额就是其总金额
        if ($coupon_amount == 0) {
            foreach ($order_detail as &$item) {
                $item['max_refund_amount'] = $item['total_amount'];
            }
            return $order_detail;
        }

        // 计算订单总金额和可退款商品总金额
        $order_total_amount = 0;
        $refundable_amount  = 0;
        foreach ($order_detail as $item) {
            $item_amount        = $item['max_refund_amount'] > 0 ? $item['max_refund_amount'] : $item['total_amount'];
            $order_total_amount += $item_amount;

            // 只计算可退款商品的金额（max_refund_amount为0表示不可退款）
            if (!isset($item['max_refund_amount']) || $item['max_refund_amount'] !== 0) {
                $refundable_amount += $item_amount;
            }
        }

        // 确保优惠金额不超过可退款商品总金额
        $coupon_amount        = min($coupon_amount, $refundable_amount);
        $remaining_coupon     = $coupon_amount;
        $remaining_refundable = $refundable_amount;
        $last_index           = count($order_detail) - 1;

        // 计算优惠券分摊比例
        foreach ($order_detail as $i => &$item) {
            // 如果max_refund_amount为0，表示该商品不可退款
            if (isset($item['max_refund_amount']) && $item['max_refund_amount'] === 0) {
                if ($type == 1) $item['coupon_amount'] = 0;
                if ($type == 2) $item['full_amount'] = 0;
                continue;
            }

            $item_amount = $item['max_refund_amount'] > 0 ? $item['max_refund_amount'] : $item['total_amount'];

            // 最后一个商品时，使用剩余优惠金额以避免四舍五入误差
            if ($i == $last_index) {
                $item_coupon_amount = $remaining_coupon;
            } else {
                $coupon_ratio         = $item_amount / $remaining_refundable;
                $item_coupon_amount   = round($coupon_amount * $coupon_ratio, 2);
                $remaining_coupon     -= $item_coupon_amount;
                $remaining_refundable -= $item_amount;
            }

            // 计算最大退款金额
            $max_refund_amount = round($item_amount - $item_coupon_amount, 2);

            // 确保退款金额不小于0且不超过商品原始金额
            $max_refund_amount = max(min($max_refund_amount, $item['total_amount']), 0);

            // 设置优惠金额或满减金额
            if ($type == 1) {
                $item['coupon_amount'] = $item_coupon_amount;
            } elseif ($type == 2) {
                $item['full_amount'] = $item_coupon_amount;
            }

            $item['max_refund_amount'] = $max_refund_amount;
        }

        return $order_detail;
    }


    /**
     * 完成订单
     * @OA\Post(
     *     tags={"订单管理"},
     *     path="/wxapp/point_order/accomplish_order",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="三选一",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="order_num",
     *         in="query",
     *         description="三选一",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_order/accomplish_order
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_order/accomplish_order
     *   api:  /wxapp/point_order/accomplish_order
     *   remark_name: 完成订单
     *
     */
    public function accomplish_order()
    {
        $this->checkAuth();
        $PointOrderInit  = new \init\PointOrderInit();//订单管理    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["user_id", "=", $this->user_id];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];

        /** 查询数据 **/
        $order_info = $PointOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("暂无数据");


        //更新订单状态
        $result = $PointOrderModel->where($where)->strict(false)->update([
            'status'          => 8,
            'accomplish_time' => time(),
            'update_time'     => time(),
        ]);
        if (!$result) $this->error("操作失败");


        $this->success("操作成功!");
    }

}
