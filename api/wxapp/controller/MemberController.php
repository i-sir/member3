<?php
// +----------------------------------------------------------------------
// | 会员中心
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
namespace api\wxapp\controller;

use think\facade\Db;

header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:*');


error_reporting(0);


class MemberController extends AuthController
{
    //    public function initialize()
    //    {
    //        parent::initialize();//初始化方法
    //    }

    /**
     * 测试用
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/index
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/index
     *   api: /wxapp/member/index
     *   remark_name: 测试用
     *
     */
    public function index()
    {
        $MemberInit  = new \init\MemberInit();//用户管理
        $MemberModel = new \initmodel\MemberModel();//用户管理


        $map    = [];
        $map[]  = ['openid', '=', $openid ?? 1];
        $result = $MemberInit->get_my_info($map);

        $this->success('请求成功', $result);
    }


    /**
     * 查询会员信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/find_member",
     *
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/find_member
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/find_member
     *   api: /wxapp/member/find_member
     *   remark_name: 查询会员信息
     *
     */
    public function find_member()
    {
        $this->checkAuth();

        $MemberModel = new \initmodel\MemberModel();//用户管理
        $MemberInit  = new \init\MemberInit();//用户管理


        $map    = [];
        $map[]  = ['openid', '=', $this->openid];
        $result = $MemberInit->get_my_info($map);

        $this->success("请求成功!", $result);
    }


    /**
     * 更新会员信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/update_member",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="nickname",
     *         in="query",
     *         description="昵称",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="手机号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="avatar",
     *         in="query",
     *         description="头像",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *      @OA\Parameter(
     *         name="used_pass",
     *         in="query",
     *         description="旧密码,如需要传,不需要请勿传",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="pass",
     *         in="query",
     *         description="更改密码,如需要传,不需要请勿传",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="姓名",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="birth",
     *         in="query",
     *         description="出生日期",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         description="所属地区",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="通讯地址",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="邮箱",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="school",
     *         in="query",
     *         description="就读学校",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="occupation",
     *         in="query",
     *         description="职位",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="associations",
     *         in="query",
     *         description="校社团",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="associations_occupation",
     *         in="query",
     *         description="校社团职位",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="urgent_username",
     *         in="query",
     *         description="紧急联系人姓名",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="urgent_phone",
     *         in="query",
     *         description="紧急联系人电话",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="relationship",
     *         in="query",
     *         description="与我关系",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/update_member
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/update_member
     *   api: /wxapp/member/update_member
     *   remark_name: 更新会员信息
     *
     */
    public function update_member()
    {
        $this->checkAuth();

        $MemberModel = new \initmodel\MemberModel();//用户管理


        $params                = $this->request->param();
        $params['update_time'] = time();
        $member                = $this->user_info;


        //        $result = $this->validate($params, 'Member');
        //        if ($result !== true) $this->error($result);


        if (empty($member)) $this->error("该会员不存在!");
        if ($member['pid']) unset($params['pid']);


        //修改密码
        if ($params['pass']) {
            if (!cmf_compare_password($params['used_pass'], $member['pass'])) $this->error('旧密码错误');
            $params['pass'] = cmf_password($params['pass']);
        }

        $result = $MemberModel->where('id', $member['id'])->strict(false)->update($params);
        if ($result) {
            $result = $this->getUserInfoByOpenid($this->openid);
            $this->success("保存成功!", $result);
        } else {
            $this->error("保存失败!");
        }
    }


    /**
     * 账户资产变动明细
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/find_asset_list",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="operate_type",
     *         in="query",
     *         description="操作字段类型:balance余额,point积分",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="change_type",
     *         in="query",
     *         description="类型:1=收入,2=支出 (选填)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
     *         name="begin_time",
     *         in="query",
     *         description="2025-01-15",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         description="2025-01-15",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/find_asset_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/find_asset_list
     *   api: /wxapp/member/find_asset_list
     *   remark_name: 账户资产变动明细
     *
     */
    public function find_asset_list()
    {
        $this->checkAuth();
        $AssetModel = new \initmodel\AssetModel();

        $params = $this->request->param();


        //数据类型
        $operate_type_list = $AssetModel->operate_type;
        if (empty($params['operate_type'])) $params['operate_type'] = array_keys($operate_type_list)[0];


        $where   = [];
        $where[] = ['user_id', '=', $this->user_id];
        $where[] = ['identity_type', '=', $this->user_info['identity_type'] ?? 'member'];
        $where[] = ['operate_type', '=', $params['operate_type'] ?? 'balance'];
        $where[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        if ($params['change_type']) $where[] = ['change_type', '=', $params['change_type'] ?? 1];

        $result = $AssetModel->where($where)
            ->field("id,user_id,order_num,operate_type,identity_type,order_type,price,content,change_type,create_time")
            ->order("id desc")
            ->paginate($params['page_size'])
            ->each(function ($item, $key) use ($operate_type_list) {

                if ($item['change_type'] == 2) {
                    $item['price'] = -$item['price'];
                } else {
                    $item['price'] = '+' . $item['price'];
                }


                return $item;
            });

        $this->success("请求成功！", $result);
    }


    /**
     * 日期列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/date_list",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="类型:1赛事,2年度",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/date_list
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/date_list
     *   api: /wxapp/member/date_list
     *   remark_name: 日期列表
     *
     */
    public function date_list()
    {
        $params = $this->request->param();

        $params['type'] = $params['type'] ?? 1;


        if ($params['type'] == 1) {
            $result[] = [
                'name'       => '赛季',
                'begin_date' => cmf_config('season_begin_date'),
                'end_date'   => cmf_config('season_end_date'),
            ];
        }
        if ($params['type'] == 2) {
            $result1[] = [
                'name'       => '生涯累计总排名',
                'begin_date' => '2000-01-01',
                'end_date'   => '2099-01-01',
            ];
            $result2   = $this->getRecentAcademicYears();
            $result    = array_merge($result1, $result2);
        }

        $this->success("请求成功！", $result);
    }


    /**
     * 排名列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @OA\Post(
     *     tags={"会员中心模块"},
     *     path="/wxapp/member/statistics",
     *
     *
     *     @OA\Parameter(
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
     *     @OA\Parameter(
     *         name="begin_time",
     *         in="query",
     *         description="开始时间",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         description="结束时间",
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
     *   test_environment: http://member3.ikun:9090/api/wxapp/member/statistics
     *   official_environment: https://xcxkf161.aubye.com/api/wxapp/member/statistics
     *   api: /wxapp/member/statistics
     *   remark_name: 排名列表
     *
     */
    public function statistics()
    {
        $params = $this->request->param();


        $AssetModel = new \initmodel\AssetModel();


        $map   = [];
        $map[] = $this->getBetweenTime($params['begin_time'], $params['end_time'], 'a.create_time');
        $map[] = ['operate_type', '=', 'match_point'];
        $map[] = ['identity_type', '=', 'member'];
        $map[] = ['change_type', '=', 1];

        // 统计每个用户
        $result = $AssetModel->alias('a')
            ->join('member m', 'a.user_id = m.id')  // 联表查询用户信息
            ->where($map)
            ->field('a.user_id, SUM(a.price) as total_price, m.nickname, m.avatar, m.phone') // 添加用户信息字段
            ->group('a.user_id') // 根据用户ID分组
            ->order('total_price desc') // 按总价降序排列
            ->limit(100)
            ->select()
            ->each(function ($item, $key) {
                if ($item['avatar']) $item['avatar'] = cmf_get_asset_url($item['avatar']);
                $item['number'] = $key + 1;

                return $item;
            });


        $this->success("请求成功！", $result);
    }


    /**
     * 获取最近 N 个年度的日期范围
     * @param int $n 年度数量
     * @return array
     */
    function getRecentAcademicYears($n = 5)
    {
        $currentYear  = date('Y');
        $currentMonth = date('m');

        // 如果当前月份在9月之后，从今年开始
        $startYear = ($currentMonth >= 9) ? $currentYear : $currentYear - 1;

        $result = [];
        for ($i = 0; $i < $n; $i++) {
            $year  = $startYear - $i;
            $range = $this->getAcademicYearDates($year);

            $result[] = [
                'name'       => $year . '-' . ($year + 1) . '年度排名',
                'begin_date' => $range['begin_date'],
                'end_date'   => $range['end_date'],
                'year'       => $year
            ];
        }

        return $result;
    }

    /**
     * 获取指定年度的开始和结束日期
     * @param int $year 年份
     * @return array 包含开始和结束日期的数组
     */
    private function getAcademicYearDates($year)
    {
        // 假设年度格式为 2024-2025，开始日期为 2024-09-01，结束日期为 2025-08-31
        $beginDate = date('Y-m-d', strtotime($year . '-01-01'));
        $endDate   = date('Y-m-d', strtotime(($year + 1) . '-12-31'));

        return [
            'begin_date' => $beginDate,
            'end_date'   => $endDate
        ];
    }

}