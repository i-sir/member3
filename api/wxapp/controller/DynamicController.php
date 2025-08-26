<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Dynamic",
 *     "name_underline"          =>"dynamic",
 *     "controller_name"         =>"Dynamic",
 *     "table_name"              =>"dynamic",
 *     "remark"                  =>"动态管理"
 *     "api_url"                 =>"/api/wxapp/dynamic/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-26 15:25:50",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\DynamicController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/dynamic/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/dynamic/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class DynamicController extends AuthController
{

    //public function initialize(){
    //	//动态管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/dynamic/index
     * https://xcxkf161.aubye.com/api/wxapp/dynamic/index
     */
    public function index()
    {
        $DynamicInit  = new \init\DynamicInit();//动态管理   (ps:InitController)
        $DynamicModel = new \initmodel\DynamicModel(); //动态管理   (ps:InitModel)

        $result = [];

        $this->success('动态管理-接口请求成功', $result);
    }


    /**
     * 动态管理 列表
     * @OA\Post(
     *     tags={"动态管理"},
     *     path="/wxapp/dynamic/find_dynamic_list",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/dynamic/find_dynamic_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/dynamic/find_dynamic_list
     *   api:  /wxapp/dynamic/find_dynamic_list
     *   remark_name: 动态管理 列表
     *
     */
    public function find_dynamic_list()
    {
        $DynamicInit  = new \init\DynamicInit();//动态管理   (ps:InitController)
        $DynamicModel = new \initmodel\DynamicModel(); //动态管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name|introduce", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $DynamicInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $DynamicInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 动态管理 详情
     * @OA\Post(
     *     tags={"动态管理"},
     *     path="/wxapp/dynamic/find_dynamic",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/dynamic/find_dynamic
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/dynamic/find_dynamic
     *   api:  /wxapp/dynamic/find_dynamic
     *   remark_name: 动态管理 详情
     *
     */
    public function find_dynamic()
    {
        $DynamicInit  = new \init\DynamicInit();//动态管理    (ps:InitController)
        $DynamicModel = new \initmodel\DynamicModel(); //动态管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $DynamicInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }




}
