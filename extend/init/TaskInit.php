<?php

namespace init;

use api\wxapp\controller\InitController;
use plugins\weipay\lib\PayController;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;

/**
 * 定时任务
 */
class TaskInit
{


    /**
     * 更新项目状态
     */
    public function operation_project()
    {
        $ProjectModel = new \initmodel\ProjectModel(); //项目预定   (ps:InitModel)


        //进行中
        $map   = [];
        $map[] = ['begin_time', '<', time()];
        $map[] = ['end_time', '>', time()];
        $map[] = ['status', '=', 2];
        $ProjectModel->where($map)->strict(false)->update([
            'status'      => 1,
            'update_time' => time(),
        ]);


        //已结束
        $map100   = [];
        $map100[] = ['end_time', '<', time()];
        $map100[] = ['status', '=', 1];
        $ProjectModel->where($map100)->strict(false)->update([
            'status'      => 2,
            'update_time' => time(),
        ]);


        echo("更新项目状态,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }

    /**
     * 更新活动状态
     */
    public function operation_activity()
    {
        $ActivityModel = new \initmodel\ActivityModel(); //赛事活动   (ps:InitModel)


        //进行中
        $map   = [];
        $map[] = ['begin_time', '<', time()];
        $map[] = ['end_time', '>', time()];
        $map[] = ['status', '=', 2];
        $ActivityModel->where($map)->strict(false)->update([
            'status'      => 1,
            'update_time' => time(),
        ]);


        //已结束
        $map100   = [];
        $map100[] = ['end_time', '<', time()];
        $map100[] = ['status', '=', 1];
        $ActivityModel->where($map100)->strict(false)->update([
            'status'      => 2,
            'update_time' => time(),
        ]);


        echo("更新活动状态,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 自动取消订单
     */
    public function operation_cancel_order()
    {
        $ShopOrderModel       = new \initmodel\ShopOrderModel(); //商城订单   (ps:InitModel)
        $ShopOrderDetailModel = new \initmodel\ShopOrderDetailModel();//订单详情
        $ShopCouponUserModel  = new \initmodel\ShopCouponUserModel(); //优惠券领取记录   (ps:InitModel)
        $StockInit            = new \init\StockInit();
        $Pay                  = new PayController();
        $OrderPayModel        = new \initmodel\OrderPayModel();

        $map   = [];
        $map[] = ['auto_cancel_time', '<', time()];
        $map[] = ['status', '=', 1];
        $list  = $ShopOrderModel->where($map)->select();

        foreach ($list as $key => $order_info) {

            //微信支付取消 && 不让再次支付了
            if (empty($order_info['pay_num'])) {
                $map300   = [];
                $map300[] = ['order_num', '=', $order_info['order_num']];
                $pay_num  = $OrderPayModel->where($map300)->value('pay_num');
            } else {
                $pay_num = $order_info['pay_num'];
            }
            $Pay->close_order($pay_num);


            //添加库存
            $order_detail = $ShopOrderDetailModel->where('order_num', '=', $order_info['order_num'])->select();
            foreach ($order_detail as $k => $v) {
                $StockInit->inc_stock('shop_goods', $v['sku_id'], $v['count'], $v['goods_id'], $order_info['order_num']);
            }


            //优惠券退回
            if ($order_info['coupon_id']) {
                $ShopCouponUserModel->where('id', '=', $order_info['coupon_id'])->update(['used' => 1, 'update_time' => time()]);
            }
        }

        $ShopOrderModel->where($map)->strict(false)->update([
            'status'      => 10,
            'cancel_time' => time(),
            'update_time' => time(),
        ]);


        echo("自动取消订单,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 自动完成订单
     */
    public function operation_accomplish_order()
    {
        $ShopOrderModel = new \initmodel\ShopOrderModel(); //商城订单   (ps:InitModel)
        $InitController = new InitController();//基础接口


        $map   = [];
        $map[] = ['auto_accomplish_time', '<', time()];
        $map[] = ['status', '=', 4];

        $list = $ShopOrderModel->where($map)->field('id,order_num')->select();
        foreach ($list as $k => $order_info) {
            //这里处理订单完成后的逻辑
            $InitController->sendShopOrderAccomplish($order_info['order_num']);
        }

        $ShopOrderModel->where($map)->strict(false)->update([
            'status'          => 8,
            'accomplish_time' => time(),
            'update_time'     => time(),
        ]);


        echo("自动取消订单,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }

    /**
     * 自动完成赛事活动
     */
    public function operation_accomplish_activity_order()
    {
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        $InitController = new InitController();//基础接口


        $map   = [];
        $map[] = ['end_time', '<', time()];
        $map[] = ['status', 'in', [2,3]];

        $list = $ActivityOrderModel->where($map)->field('id,order_num')->select();
        foreach ($list as $k => $order_info) {
            //这里处理订单完成后的逻辑
            $InitController->sendActivityOrderAccomplish($order_info['order_num']);
        }

        $ActivityOrderModel->where($map)->strict(false)->update([
            'status'          => 8,
            'accomplish_time' => time(),
            'update_time'     => time(),
        ]);


        echo("自动完成赛事活动,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 更新vip状态
     */
    public function operation_vip()
    {
        $MemberModel = new \initmodel\MemberModel();//用户管理

        //操作vip   vip_time vip到期时间
        $MemberModel->where('vip_end_time', '<', time())->update(['is_vip' => 0]);
        echo("更新vip状态,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }


    /**
     * 将公众号的official_openid存入member表中
     */
    public function update_official_openid()
    {
        $gzh_list = Db::name('member_gzh')->select();
        foreach ($gzh_list as $k => $v) {
            Db::name('member')->where('unionid', '=', $v['unionid'])->update(['official_openid' => $v['openid']]);
        }

        echo("将公众号的official_openid存入member表中,执行成功\n" . cmf_random_string(80) . "\n" . date('Y-m-d H:i:s') . "\n");
    }

}