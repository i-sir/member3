<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"PointOrder",
 *     "name_underline"      =>"point_order",
 *     "controller_name"     =>"PointOrder",
 *     "table_name"          =>"point_order",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"订单管理",
 *     "author"              =>"",
 *     "create_time"         =>"2025-08-27 16:44:05",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\PointOrderController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class PointOrderController extends AdminBaseController
{

    // public function initialize(){
    //	//订单管理
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

    }


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'PointOrder',
     *     'name_underline'   => 'point_order',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '订单管理',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $this->base_index();//处理基础信息


        $params          = $this->request->param();
        $PointOrderInit  = new \init\PointOrderInit();//积分商城订单    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //积分商城订单   (ps:InitModel)

        $where   = [];
        $where[] = ['status', 'in', [2, 4, 8]];
        if ($params['keyword']) $where[] = ['phone|username|order_num', 'like', "%{$params['keyword']}%"];
        if ($params['order_num']) $where[] = ['order_num', 'like', "%{$params['order_num']}%"];
        if ($params['goods_name']) $where[] = ['goods_name', 'like', "%{$params['goods_name']}%"];
        if ($params['user_id']) $where[] = ['user_id', '=', $params['user_id']];


        if ($params['order_date']) {
            $order_date_arr = explode(' - ', $params['order_date']);
            $where[]        = $this->getBetweenTime($order_date_arr[0], $order_date_arr[1]);
        }


        //状态筛选
        $status_where = [];
        if ($params['status']) $status_where[] = ['status', 'in', $PointOrderInit->admin_status_where[$params['status']]];
        //if (empty($params['status'])) $status_where[] = ['status', 'in', [2, 3]];


        //数据类型
        $params['InterfaceType'] = 'admin';//身份类型,后台


        //导出数据
        //if ($params["is_export"]) $PointOrderInit->export_excel(array_merge($where, $status_where), $params);
        $result = $PointOrderInit->get_list_paginate(array_merge($where, $status_where), $params);


        $this->assign("list", $result);
        $this->assign('page', $result->render());//单独提取分页出来

        //全部数量
        $this->assign("total", $PointOrderModel->where($where)->count());//总数量


        //数据统计
        $status_arr = $PointOrderInit->status_list;
        $count      = [];
        foreach ($status_arr as $key => $status) {
            $map                    = [];
            $map[]                  = ['status', '=', $key];
            $map                    = array_merge($map, $where);
            $count[$key]['count']   = $PointOrderModel->where($map)->count();
            $count[$key]['key']     = $key;
            $count[$key]['name']    = $status;
            $count[$key]['is_ture'] = false;
            if ($params['status'] == $key) $count[$key]['is_ture'] = true;
        }


        $this->assign('count', $count);


        return $this->fetch();

    }


    //查看详情
    public function details()
    {
        $params         = $this->request->param();
        $PointOrderInit = new \init\PointOrderInit();//积分商城订单    (ps:InitController)


        $where   = [];
        $where[] = ['id', '=', $params['id']];
        $result  = $PointOrderInit->get_find($where);


        if (empty($result)) $this->error("暂无数据");


        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }


        return $this->fetch();
    }


    //查看详情
    public function find()
    {
        $this->base_edit();//处理基础信息

        $PointOrderInit  = new \init\PointOrderInit();//订单管理    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $PointOrderInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //发货
    public function send()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];


        $result = $PointOrderInit->get_find($where);
        if (empty($result)) $this->error("暂无数据");
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        //快递公司
        $express = Db::name('base_express')->select();
        $this->assign('express', $express);

        return $this->fetch();
    }


    //发货提交
    public function send_post()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理    (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        //订单发货后自动完成时间 单位/天
        $order_auto_completion_time = cmf_config('order_auto_completion_time');

        $params     = $this->request->param();
        $order_info = $PointOrderInit->get_find($params['id']);
        if (empty($order_info)) $this->error('订单信息错误');

        if (empty($params['exp_num'])) $this->error('快递单号不能为空');


        //快递信息
        $express_info = Db::name('base_express')->find($params['exp_id']);

        //更改订单信息
        $params['exp_name']             = $express_info['name'];//快递名称
        $params['status']               = 4;
        $params['send_time']            = time();
        $params['auto_accomplish_time'] = time() + $order_auto_completion_time * 86400;//自动完成时间
        $PointOrderInit->edit_post($params);


        //        $map     = [];
        //        $map[]   = ['order_num', '=', $order_info['order_num']];
        //        $map[]   = ['status', '=', 2];
        //        $pay_num = Db::name('base_order_pay')->where($map)->value('pay_num');
        //
        //        //微信支付&发货
        //        if ($order_info['pay_type'] != 2) {
        //            $phone   = $order_info['phone'];
        //            $exp_num = $params['exp_num'];
        //            //发货
        //            $openid           = $order_info['openid'];
        //            $WxBaseController = new WxBaseController();
        //
        //
        //            if ($params['is_virtual'] == 2) {
        //                //虚拟发货
        //                $send_result = $WxBaseController->uploadShippingInfo($pay_num, $openid, '订单发货', 3);
        //            } else {
        //                //快递发货
        //                $send_result = $WxBaseController->uploadShippingInfo($pay_num, $openid, '订单发货', 1, $express_info['abbr'], $exp_num, $phone);
        //            }
        //
        //            if ($send_result) {
        //                Log::write('uploadShippingInfo-');
        //                Log::write($send_result);
        //            }
        //        }


        $this->success('发货成功');
    }


    //核销订单
    public function verification_order()
    {
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();

        /** 查询条件 **/
        $where = [];
        if ($params['id']) $where[] = ["id", "=", $params["id"]];
        if ($params['order_num']) $where[] = ["order_num", "=", $params["order_num"]];
        if ($params['cav_code']) $where[] = ["cav_code", "=", $params["cav_code"]];


        /** 查询数据 **/
        $order_info = $PointOrderModel->where($where)->find();
        if (empty($order_info)) $this->error("暂无数据");
        if ($order_info['status'] != 2) $this->error("订单状态错误");


        $result = $PointOrderModel->where($where)->strict(false)->update([
            "status"          => 8,
            "update_time"     => time(),
            "accomplish_time" => time(),
        ]);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }


    //驳回
    public function refuse()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理  (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $PointOrderInit->get_find($where, $params);
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
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $item                    = $PointOrderInit->get_find($where);
        if (empty($item)) $this->error("暂无数据");

        /** 通过&拒绝时间 **/
        if ($params['status'] == 2) $params['pass_time'] = time();
        if ($params['status'] == 3) $params['refuse_time'] = time();

        /** 提交数据 **/
        $result = $PointOrderInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }

    //删除
    public function delete()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param();

        if ($params["id"]) $id = $params["id"];
        if (empty($params["id"])) $id = $this->request->param("ids/a");

        /** 删除数据 **/
        $result = $PointOrderInit->delete_post($id);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");//   , "index{$this->params_url}"
    }


    //批量操作
    public function batch_post()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param();

        $id = $this->request->param("id/a");
        if (empty($id)) $id = $this->request->param("ids/a");

        //提交编辑
        $result = $PointOrderInit->batch_post($id, $params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功");//   , "index{$this->params_url}"
    }


    //更新排序
    public function list_order_post()
    {
        $PointOrderInit  = new \init\PointOrderInit();//订单管理   (ps:InitController)
        $PointOrderModel = new \initmodel\PointOrderModel(); //订单管理   (ps:InitModel)
        $params          = $this->request->param("list_order/a");

        //提交更新
        $result = $PointOrderInit->list_order_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功"); //   , "index{$this->params_url}"
    }


}
