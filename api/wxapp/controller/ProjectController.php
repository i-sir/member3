<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Project",
 *     "name_underline"          =>"project",
 *     "controller_name"         =>"Project",
 *     "table_name"              =>"project",
 *     "remark"                  =>"项目预定"
 *     "api_url"                 =>"/api/wxapp/project/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-26 17:09:37",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ProjectController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/project/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/project/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ProjectController extends AuthController
{

    //public function initialize(){
    //	//项目预定
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/project/index
     * https://xcxkf161.aubye.com/api/wxapp/project/index
     */
    public function index()
    {
        $ProjectInit  = new \init\ProjectInit();//项目预定   (ps:InitController)
        $ProjectModel = new \initmodel\ProjectModel(); //项目预定   (ps:InitModel)

        $result = [];

        $this->success('项目预定-接口请求成功', $result);
    }


    /**
     * 项目预定 列表
     * @OA\Post(
     *     tags={"项目预定"},
     *     path="/wxapp/project/find_project_list",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/project/find_project_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project/find_project_list
     *   api:  /wxapp/project/find_project_list
     *   remark_name: 项目预定 列表
     *
     */
    public function find_project_list()
    {
        $ProjectInit  = new \init\ProjectInit();//项目预定   (ps:InitController)
        $ProjectModel = new \initmodel\ProjectModel(); //项目预定   (ps:InitModel)

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
        if ($params['is_paginate']) $result = $ProjectInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ProjectInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 项目预定 详情
     * @OA\Post(
     *     tags={"项目预定"},
     *     path="/wxapp/project/find_project",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/project/find_project
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/project/find_project
     *   api:  /wxapp/project/find_project
     *   remark_name: 项目预定 详情
     *
     */
    public function find_project()
    {
        $ProjectInit  = new \init\ProjectInit();//项目预定    (ps:InitController)
        $ProjectModel = new \initmodel\ProjectModel(); //项目预定   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ProjectInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
