<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"News",
 *     "name_underline"          =>"news",
 *     "controller_name"         =>"News",
 *     "table_name"              =>"news",
 *     "remark"                  =>"消息管理"
 *     "api_url"                 =>"/api/wxapp/news/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-08-26 16:46:01",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\NewsController();
 *     "test_environment"        =>"http://member3.ikun:9090/api/wxapp/news/index",
 *     "official_environment"    =>"https://xcxkf161.aubye.com/api/wxapp/news/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class NewsController extends AuthController
{

    //public function initialize(){
    //	//消息管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/news/index
     * https://xcxkf161.aubye.com/api/wxapp/news/index
     */
    public function index()
    {
        $NewsInit  = new \init\NewsInit();//消息管理   (ps:InitController)
        $NewsModel = new \initmodel\NewsModel(); //消息管理   (ps:InitModel)

        $result = [];

        $this->success('消息管理-接口请求成功', $result);
    }


    /**
     * 消息管理 列表
     * @OA\Post(
     *     tags={"消息管理"},
     *     path="/wxapp/news/find_news_list",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/news/find_news_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/news/find_news_list
     *   api:  /wxapp/news/find_news_list
     *   remark_name: 消息管理 列表
     *
     */
    public function find_news_list()
    {
        $NewsInit  = new \init\NewsInit();//消息管理   (ps:InitController)
        $NewsModel = new \initmodel\NewsModel(); //消息管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name|image", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $NewsInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $NewsInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 消息管理 详情
     * @OA\Post(
     *     tags={"消息管理"},
     *     path="/wxapp/news/find_news",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/news/find_news
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/news/find_news
     *   api:  /wxapp/news/find_news
     *   remark_name: 消息管理 详情
     *
     */
    public function find_news()
    {
        $NewsInit  = new \init\NewsInit();//消息管理    (ps:InitController)
        $NewsModel = new \initmodel\NewsModel(); //消息管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $NewsInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
