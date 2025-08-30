<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Activity",
 *     "name_underline"          =>"activity",
 *     "controller_name"         =>"Activity",
 *     "table_name"              =>"activity",
 *     "remark"                  =>"赛事活动"
 *     "api_url"                 =>"/api/wxapp/activity/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-28 10:29:49",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/activity/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/activity/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ActivityController extends AuthController
{

    //public function initialize(){
    //	//赛事活动
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity/index
     * https://xcxkf161.aubye.com/api/wxapp/activity/index
     */
    public function index()
    {
        $ActivityInit  = new \init\ActivityInit();//赛事活动   (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //赛事活动   (ps:InitModel)

        $result = [];

        $this->success('赛事活动-接口请求成功', $result);
    }


    /**
     * 赛事活动 列表
     * @OA\Post(
     *     tags={"赛事活动"},
     *     path="/wxapp/activity/find_activity_list",
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
     *         description="状态:1报名中,2已结束",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity/find_activity_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity/find_activity_list
     *   api:  /wxapp/activity/find_activity_list
     *   remark_name: 赛事活动 列表
     *
     */
    public function find_activity_list()
    {
        $ActivityInit  = new \init\ActivityInit();//赛事活动   (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //赛事活动   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['type']) $where[] = ['type', '=', $params['type']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ActivityInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ActivityInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 赛事活动 详情
     * @OA\Post(
     *     tags={"赛事活动"},
     *     path="/wxapp/activity/find_activity",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/activity/find_activity
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/activity/find_activity
     *   api:  /wxapp/activity/find_activity
     *   remark_name: 赛事活动 详情
     *
     */
    public function find_activity()
    {
        $ActivityInit  = new \init\ActivityInit();//赛事活动    (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //赛事活动   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
