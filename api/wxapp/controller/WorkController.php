<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Work",
 *     "name_underline"          =>"work",
 *     "controller_name"         =>"Work",
 *     "table_name"              =>"work",
 *     "remark"                  =>"岗位管理"
 *     "api_url"                 =>"/api/wxapp/work/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-27 15:11:44",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\WorkController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/work/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/work/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class WorkController extends AuthController
{

    //public function initialize(){
    //	//岗位管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/work/index
     * https://xcxkf161.aubye.com/api/wxapp/work/index
     */
    public function index()
    {
        $WorkInit  = new \init\WorkInit();//岗位管理   (ps:InitController)
        $WorkModel = new \initmodel\WorkModel(); //岗位管理   (ps:InitModel)

        $result = [];

        $this->success('岗位管理-接口请求成功', $result);
    }


    /**
     * 岗位管理 列表
     * @OA\Post(
     *     tags={"岗位管理"},
     *     path="/wxapp/work/find_work_list",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work/find_work_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work/find_work_list
     *   api:  /wxapp/work/find_work_list
     *   remark_name: 岗位管理 列表
     *
     */
    public function find_work_list()
    {
        $WorkInit  = new \init\WorkInit();//岗位管理   (ps:InitController)
        $WorkModel = new \initmodel\WorkModel(); //岗位管理   (ps:InitModel)

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


        if ($params['type'] == 3 && $this->user_info['vip_end_time'] < time()) $this->error("会员已到期,请续费!");

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $WorkInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $WorkInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 岗位管理 详情
     * @OA\Post(
     *     tags={"岗位管理"},
     *     path="/wxapp/work/find_work",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/work/find_work
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/work/find_work
     *   api:  /wxapp/work/find_work
     *   remark_name: 岗位管理 详情
     *
     */
    public function find_work()
    {
        $WorkInit  = new \init\WorkInit();//岗位管理    (ps:InitController)
        $WorkModel = new \initmodel\WorkModel(); //岗位管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $WorkInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
