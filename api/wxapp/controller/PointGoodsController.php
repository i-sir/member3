<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"PointGoods",
 *     "name_underline"          =>"point_goods",
 *     "controller_name"         =>"PointGoods",
 *     "table_name"              =>"point_goods",
 *     "remark"                  =>"商品管理"
 *     "api_url"                 =>"/api/wxapp/point_goods/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-27 16:43:18",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\PointGoodsController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/point_goods/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/point_goods/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class PointGoodsController extends AuthController
{

    //public function initialize(){
    //	//商品管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/point_goods/index
     * https://xcxkf161.aubye.com/api/wxapp/point_goods/index
     */
    public function index()
    {
        $PointGoodsInit  = new \init\PointGoodsInit();//商品管理   (ps:InitController)
        $PointGoodsModel = new \initmodel\PointGoodsModel(); //商品管理   (ps:InitModel)

        $result = [];

        $this->success('商品管理-接口请求成功', $result);
    }


    /**
     * 分类列表
     * @OA\Post(
     *     tags={"商品管理"},
     *     path="/wxapp/point_goods/find_class_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_goods/find_class_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_goods/find_class_list
     *   api:  /wxapp/point_goods/find_class_list
     *   remark_name: 分类列表
     *
     */
    public function find_class_list()
    {
        $PointGoodsClassInit  = new \init\PointGoodsClassInit();//分类管理   (ps:InitController)
        $PointGoodsClassModel = new \initmodel\PointGoodsClassModel(); //分类管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];

        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['is_index']) $where[] = ['is_index', '=', 1];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $list                    = $PointGoodsClassInit->get_list($where, $params);


        $index_data[] = [
            [
                'id'   => 0,
                'name' => '全部'
            ], [
                'id'   => 1,
                'name' => '我能兑换'
            ]
        ];
        $result       = array_merge($index_data, $list->toArray());

        $this->success("请求成功!", $result);
    }


    /**
     * 商品管理 列表
     * @OA\Post(
     *     tags={"商品管理"},
     *     path="/wxapp/point_goods/find_goods_list",
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
     *    @OA\Parameter(
     *         name="class_id",
     *         in="query",
     *         description="分类ID",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_goods/find_goods_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_goods/find_goods_list
     *   api:  /wxapp/point_goods/find_goods_list
     *   remark_name: 商品管理 列表
     *
     */
    public function find_goods_list()
    {
        $PointGoodsInit  = new \init\PointGoodsInit();//商品管理   (ps:InitController)
        $PointGoodsModel = new \initmodel\PointGoodsModel(); //商品管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["goods_name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['is_index']) $where[] = ['is_index', '=', 1];
        if ($params['type']) $where[] = ['type', '=', $params['type']];
        if ($params['class_id'] == 1) {
            $this->checkAuth();
            $where[] = ['price', '<=', $this->user_info['point']];
        } elseif ($params['class_id']) {
            $where[] = ['class_id', '=', $params['class_id']];
        }


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $PointGoodsInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $PointGoodsInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 商品管理 详情
     * @OA\Post(
     *     tags={"商品管理"},
     *     path="/wxapp/point_goods/find_goods",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/point_goods/find_goods
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/point_goods/find_goods
     *   api:  /wxapp/point_goods/find_goods
     *   remark_name: 商品管理 详情
     *
     */
    public function find_goods()
    {
        $PointGoodsInit  = new \init\PointGoodsInit();//商品管理    (ps:InitController)
        $PointGoodsModel = new \initmodel\PointGoodsModel(); //商品管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $PointGoodsInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
