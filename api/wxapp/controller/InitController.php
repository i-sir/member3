<?php

namespace api\wxapp\controller;

use initmodel\AssetModel;
use initmodel\MemberModel;

/**
 * @ApiController(
 *     "name"                    =>"Init",
 *     "name_underline"          =>"init",
 *     "controller_name"         =>"Init",
 *     "table_name"              =>"无",
 *     "remark"                  =>"基础接口,封装的接口"
 *     "api_url"                 =>"/api/wxapp/init/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2024-04-24 17:16:22",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\InitController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/init/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/init/index",
 * )
 */
class InitController
{
    /**
     * 本模块,用于封装常用方法,复用方法
     */


    /**
     * 给上级发放佣金
     * @param $p_user_id 上级id
     * @param $child_id  子级id
     *                   https://xcxkf161.aubye.com/api/wxapp/init/send_invitation_commission?p_user_id=1
     */
    public function send_invitation_commission($p_user_id = 0, $child_id = 0)
    {
        //邀请佣金
        $price  = cmf_config('invitation_rewards');
        $remark = "操作人[邀请奖励];操作说明[邀请好友得佣金];操作类型[佣金奖励];";//管理备注

        AssetModel::incAsset('邀请注册奖励,给上级发放佣金 [120]', [
            'operate_type'  => 'balance',//操作类型，balance|point ...
            'identity_type' => 'member',//身份类型，member| ...
            'user_id'       => $p_user_id,
            'price'         => $price,
            'order_num'     => cmf_order_sn(),
            'order_type'    => 120,
            'content'       => '邀请奖励',
            'remark'        => $remark,
            'order_id'      => 0,
            'child_id'      => $child_id
        ]);

        return true;
    }


    /**
     * 订单完成,赠送积分
     * @param $order_num
     */
    public function sendShopOrderAccomplish($order_num)
    {
        $ShopOrderModel = new \initmodel\ShopOrderModel();//订单管理


        $map        = [];
        $map[]      = ['order_num', '=', $order_num];
        $order_info = $ShopOrderModel->where($map)->find();
        if (empty($order_info)) return false;


        //不同项目配置
        return false;

        //订单完成赠送积分比例(%)     订单金额*比例=实际到账积分
        $order_completion_reward_points = cmf_config('order_completion_reward_points');


        $points = $order_info['amount'] * ($order_completion_reward_points / 100);


        $remark = "操作人[下单得积分];操作说明[下单得积分];操作类型[下单得积分];";//管理备注

        AssetModel::incAsset('下单得积分,给上级发放佣金 [220]', [
            'operate_type'  => 'point',//操作类型，balance|point ...
            'identity_type' => 'member',//身份类型，member| ...
            'user_id'       => $order_info['user_id'],
            'price'         => $points,
            'order_num'     => $order_num,
            'order_type'    => 220,
            'content'       => '下单奖励',
            'remark'        => $remark,
            'order_id'      => 0,
        ]);

        return true;
    }


    /**
     * 赛事活动支付成功
     * @param $order_num
     */
    public function payActivity($order_num)
    {
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        $map        = [];
        $map[]      = ['order_num', '=', $order_num];
        $order_info = $ActivityOrderModel->where($map)->find();
        if (empty($order_info)) return false;
        if ($order_info['order_count'] != 1) return false;


        if ($order_num['base_point'] > 0) {
            $remark = "操作人[支付成功,发放固定冻结积分];操作说明[支付成功,发放固定冻结积分];操作类型[支付成功,发放固定冻结积分];";//管理备注
            AssetModel::incAsset('赛事支付成功,发放固定冻结积分[310]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['base_point'],
                'order_num'     => $order_num,
                'order_type'    => 310,
                'content'       => '赛事固定积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['reward_point'] > 0) {
            $remark = "操作人[支付成功,发放奖励冻结积分];操作说明[支付成功,发放奖励冻结积分];操作类型[支付成功,发放奖励冻结积分];";//管理备注
            AssetModel::incAsset('赛事支付成功,发放固定冻结积分[320]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['reward_point'],
                'order_num'     => $order_num,
                'order_type'    => 320,
                'content'       => '赛事奖励积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['invite_point'] > 0 && $order_info['pid']) {
            $remark = "操作人[支付成功,发放推荐冻结积分];操作说明[支付成功,发放推荐冻结积分];操作类型[支付成功,发放推荐冻结积分];";//管理备注
            AssetModel::incAsset('赛事支付成功,发放推荐冻结积分[330]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['pid'],
                'price'         => $order_info['invite_point'],
                'order_num'     => $order_num,
                'order_type'    => 330,
                'content'       => '赛事推荐积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['early_point'] > 0) {
            $remark = "操作人[支付成功,发放早鸟冻结积分];操作说明[支付成功,发放早鸟冻结积分];操作类型[支付成功,发放早鸟冻结积分];";//管理备注
            AssetModel::incAsset('赛事支付成功,发放早鸟冻结积分[340]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['early_point'],
                'order_num'     => $order_num,
                'order_type'    => 340,
                'content'       => '赛事早鸟积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        return true;

    }


    /**
     * 赛事活动取消订单
     * @param $order_num
     */
    public function cancelActivity($order_num)
    {
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        $map        = [];
        $map[]      = ['order_num', '=', $order_num];
        $order_info = $ActivityOrderModel->where($map)->find();
        if (empty($order_info)) return false;
        if ($order_info['order_count'] != 1) return false;


        if ($order_num['base_point'] > 0) {
            $remark = "操作人[取消订单,扣除固定冻结积分];操作说明[取消订单,扣除固定冻结积分];操作类型[取消订单,扣除固定冻结积分];";//管理备注
            AssetModel::decAsset('取消订单,扣除固定冻结积分[350]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['base_point'],
                'order_num'     => $order_num,
                'order_type'    => 350,
                'content'       => '扣除赛事固定积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['reward_point'] > 0) {
            $remark = "操作人[取消订单,扣除奖励冻结积分];操作说明[取消订单,扣除奖励冻结积分];操作类型[取消订单,扣除奖励冻结积分];";//管理备注
            AssetModel::decAsset('取消订单,扣除奖励冻结积分[360]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['reward_point'],
                'order_num'     => $order_num,
                'order_type'    => 360,
                'content'       => '扣除赛事奖励积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['invite_point'] > 0 && $order_info['pid']) {
            $remark = "操作人[取消订单,扣除推荐冻结积分];操作说明[取消订单,扣除推荐冻结积分];操作类型[取消订单,扣除推荐冻结积分];";//管理备注
            AssetModel::decAsset('取消订单,扣除推荐冻结积分[370]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['pid'],
                'price'         => $order_info['invite_point'],
                'order_num'     => $order_num,
                'order_type'    => 370,
                'content'       => '扣除赛事推荐积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['early_point'] > 0) {
            $remark = "操作人[取消订单,扣除早鸟冻结积分];操作说明[取消订单,扣除早鸟冻结积分];操作类型[取消订单,扣除早鸟冻结积分];";//管理备注
            AssetModel::decAsset('取消订单,扣除早鸟冻结积分[350]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['early_point'],
                'order_num'     => $order_num,
                'order_type'    => 350,
                'content'       => '扣除赛事早鸟积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        return true;

    }


    /**
     * 赛事活动,完成,发放积分
     * @param $order_num
     */
    public function sendActivityOrderAccomplish($order_num)
    {
        $ActivityOrderModel = new \initmodel\ActivityOrderModel(); //活动报名   (ps:InitModel)

        $map        = [];
        $map[]      = ['order_num', '=', $order_num];
        $order_info = $ActivityOrderModel->where($map)->find();
        if (empty($order_info)) return false;
        if ($order_info['order_count'] != 1) return false;


        if ($order_num['base_point'] > 0) {
            $remark = "操作人[完成订单,扣除固定冻结积分];操作说明[完成订单,扣除固定冻结积分];操作类型[完成订单,扣除固定冻结积分];";//管理备注
            AssetModel::decAsset('完成订单,扣除固定冻结积分[351]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['base_point'],
                'order_num'     => $order_num,
                'order_type'    => 351,
                'content'       => '扣除赛事固定积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);


            $remark = "操作人[完成订单,发放固定积分];操作说明[完成订单,发放固定积分];操作类型[完成订单,发放固定积分];";//管理备注
            AssetModel::incAsset('完成订单,发放固定积分[280]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['base_point'],
                'order_num'     => $order_num,
                'order_type'    => 280,
                'content'       => '赛事固定积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);

            $remark = "操作人[完成订单,增加固定积分];操作说明[完成订单,增加固定积分];操作类型[完成订单,增加固定积分];";//管理备注
            AssetModel::incAsset('完成订单,增加固定积分[400]', [
                'operate_type'  => 'match_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['base_point'],
                'order_num'     => $order_num,
                'order_type'    => 400,
                'content'       => '赛事固定积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['reward_point'] > 0) {
            $remark = "操作人[完成订单,扣除奖励冻结积分];操作说明[完成订单,扣除奖励冻结积分];操作类型[完成订单,扣除奖励冻结积分];";//管理备注
            AssetModel::decAsset('完成订单,扣除奖励冻结积分[361]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['reward_point'],
                'order_num'     => $order_num,
                'order_type'    => 361,
                'content'       => '扣除赛事奖励积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);

            $remark = "操作人[完成订单,发放奖励积分];操作说明[完成订单,发放奖励积分];操作类型[完成订单,发放奖励积分];";//管理备注
            AssetModel::incAsset('完成订单,发放奖励积分[281]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['reward_point'],
                'order_num'     => $order_num,
                'order_type'    => 281,
                'content'       => '赛事奖励积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['invite_point'] > 0 && $order_info['pid']) {
            $remark = "操作人[完成订单,扣除推荐冻结积分];操作说明[完成订单,扣除推荐冻结积分];操作类型[完成订单,扣除推荐冻结积分];";//管理备注
            AssetModel::decAsset('取消订单,扣除推荐冻结积分[371]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['pid'],
                'price'         => $order_info['invite_point'],
                'order_num'     => $order_num,
                'order_type'    => 371,
                'content'       => '扣除赛事推荐积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);


            $remark = "操作人[完成订单,发放推荐积分];操作说明[完成订单,发放推荐积分];操作类型[完成订单,发放推荐积分];";//管理备注
            AssetModel::incAsset('完成订单,发放推荐积分[282]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['pid'],
                'price'         => $order_info['invite_point'],
                'order_num'     => $order_num,
                'order_type'    => 282,
                'content'       => '赛事推荐积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        if ($order_num['early_point'] > 0) {
            $remark = "操作人[完成订单,扣除早鸟冻结积分];操作说明[完成订单,扣除早鸟冻结积分];操作类型[完成订单,扣除早鸟冻结积分];";//管理备注
            AssetModel::decAsset('完成订单,扣除早鸟冻结积分[351]', [
                'operate_type'  => 'freeze_point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['early_point'],
                'order_num'     => $order_num,
                'order_type'    => 351,
                'content'       => '扣除赛事早鸟积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);


            $remark = "操作人[完成订单,发放早鸟积分];操作说明[完成订单,发放早鸟积分];操作类型[完成订单,发放早鸟积分];";//管理备注
            AssetModel::incAsset('完成订单,发放推荐积分[283]', [
                'operate_type'  => 'point',//操作类型，balance|point ...
                'identity_type' => 'member',//身份类型，member| ...
                'user_id'       => $order_info['user_id'],
                'price'         => $order_info['early_point'],
                'order_num'     => $order_num,
                'order_type'    => 283,
                'content'       => '发放早鸟积分',
                'remark'        => $remark,
                'order_id'      => 0,
            ]);
        }


        return true;

    }


}