<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/16
 * Time: 11:21
 */

namespace app\api\controller\cms;

use app\lib\auth\AuthMap;
use LinCmsTp5\admin\exception\group\GroupException;
use LinCmsTp5\admin\model\LinAuth;
use LinCmsTp5\admin\model\LinGroup;
use LinCmsTp5\admin\model\LinUser;
use think\facade\Hook;
use think\Request;

/**
 * Class Admin
 * @doc('管理类')
 * @group('cms/admin')
 * @package app\api\controller\cms
 * @inheritDoc('@auth注解函数说明：第一个是权限名称，第二个是权限模块名称，第三个是否隐藏权限不可分配')
 */
class Admin
{

    /**
     * @doc('查询所有用户')
     * @auth('查询所有用户','管理员','hidden')
     * @route('users/:group_id','get')
     * @success('{
            "collection": [
                {
                    "id": 2,
                    "nickname": "test",
                    "email": "21@qq.com",
                    "admin": 1,
                    "active": 1,
                    "group_id": 1,
                    "create_time": "2019-08-05 17:14:25",
                    "avatar": null,
                    "group_name": null
                },
                {
                    "id": 3,
                    "nickname": "test1",
                    "email": "211@qq.com",
                    "admin": 1,
                    "active": 1,
                    "group_id": 1,
                    "create_time": "2019-08-05 17:15:35",
                    "avatar": null,
                    "group_name": null
                }
            ],
            "total_nums": 2
        }')
     * @error('')
     * @param Request $request
     * @return array
     * @throws \think\exception\DbException
     */
    public function getAdminUsers(Request $request)
    {
        $params = $request->get();

        $result = LinUser::getAdminUsers($params);
        return $result;
    }

    /**
     * @doc('修改用户密码')
     * @auth('修改用户密码','管理员','hidden')
     * @route('password/:uid','put')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "密码修改成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . Undefined index: new_password",
        "request_url": "cms\/admin\/password\/2"
        }')
     * @param Request $request
     * @param('new_password','用户密码','require|alphaDash|length:3,16')
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     */
    public function changeUserPassword(Request $request)
    {
        $params = $request->param();

        LinUser::resetPassword($params);
        return writeJson(201, '', '密码修改成功');
    }

    /**
     * @doc('删除用户')
     * @auth('删除用户','管理员','hidden')
     * @route(':uid','delete')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "操作成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 账户不存在",
        "request_url": "cms\/admin\/3"
        }')
     * @param('uid','用户uid','require|integer|between:2,10000000000')
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     */
    public function deleteUser($uid)
    {
        LinUser::deleteUser($uid);
        Hook::listen('logger', '删除了用户id为' . $uid . '的用户');
        return writeJson(201, '', '操作成功');
    }

    /**
     * @doc('管理员更新用户信息')
     * @auth('管理员更新用户信息','管理员','hidden')
     * @route(':uid','put')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "操作成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 参数验证 .   用户email不能为空",
        "request_url": "cms\/admin\/4"
        }')
     * @param Request $request
     * @param('email','用户email','require|email')
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \LinCmsTp5\admin\exception\user\UserException
     */
    public function updateUser(Request $request)
    {
        $params = $request->param();
        LinUser::updateUser($params);

        return writeJson(201, '', '操作成功');
    }

    /**
     * @doc('查询所有权限组')
     * @auth('查询所有权限组','管理员','hidden')
     * @route('group/all','get')
     * @return mixed
     */
    public function getGroupAll()
    {
        $result = LinGroup::all();

        return $result;
    }

    /**
     * @doc('查询一个权限组及其权限')
     * @auth('查询一个权限组及其权限','管理员','hidden')
     * @route('group/:id','get')
     * @success('{
        "id": 2,
        "name": "34234",
        "info": "ewgrbesxa",
        "auths": [
                {
                    "日志": [
                        {
                        "auth": "查询所有日志",
                        "module": "日志"
                        },
                        {
                        "auth": "搜索日志",
                        "module": "日志"
                        },
                        {
                        "auth": "查询日志记录的用户",
                        "module": "日志"
                        }
                    ]
                }
            ]
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 指定的分组不存在",
        "request_url": "cms\/admin\/group\/1"
        }')
     * @param $id
     * @return array|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws GroupException
     */
    public function getGroup($id)
    {
        $result = LinGroup::getGroupByID($id);

        return $result;
    }


    /**
     * @doc('删除一个权限组')
     * @auth('删除一个权限组','管理员','hidden')
     * @route('group/:id','delete')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "删除分组成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 分组下存在用户，删除分组失败",
        "request_url": "cms\/admin\/group\/1"
        }')
     * @param $id
     * @return \think\response\Json
     * @throws GroupException
     */
    public function deleteGroup($id)
    {
        //查询当前权限组下是否存在用户
        $hasUser = LinUser::get(['group_id'=>$id]);
        if($hasUser)
        {
            throw new GroupException([
                'code' => 412,
                'msg' => '分组下存在用户，删除分组失败',
                'error_code' => 30005
            ]);
        }
        LinGroup::deleteGroupAuth($id);
        Hook::listen('logger', '删除了权限组id为' . $id . '的权限组');
        return writeJson(201, '', '删除分组成功');
    }

    /**
     * @doc('新建权限组')
     * @auth('新建权限组','管理员','hidden')
     * @route('group','post')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 分组已存在",
        "request_url": "cms\/admin\/group"
        }')
     * @param Request $request
     * @return \think\response\Json
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws GroupException
     */
    public function createGroup(Request $request)
    {
        $params = $request->post();

        LinGroup::createGroup($params);
        return writeJson(201, '', '成功');
    }

    /**
     * @doc('更新一个权限组')
     * @auth('更新一个权限组','管理员','hidden')
     * @route('group/:id','put')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "更新分组成功"
        }')
     * @error('')
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     * @throws GroupException
     */
    public function updateGroup(Request $request, $id)
    {
        $params = $request->put();
        $group = LinGroup::find($id);
        if (!$group) {
            throw new GroupException([
                'code' => 404,
                'msg' => '指定的分组不存在',
                'errorCode' => 30003
            ]);
        }
        $group->save($params);
        return writeJson(201, '', '更新分组成功');
    }

    /**
     * @doc('查询所有可分配的权限')
     * @auth('查询所有可分配的权限','管理员','hidden')
     * @route('authority','get')
     * @success('{
            "日志": {
                "查询所有日志": [
                    ""
                ],
                "搜索日志": [
                    ""
                 ],
                "查询日志记录的用户": [
                    ""
                 ]
            },
            "图书": {
                "删除图书": [
                    ""
                ]
            }
        }')
     * @error('{
        "code": 10000,
        "message": "请求未携带authorization信息",
        "request_url": "cms/admin/authority"
        }')
     * @return array
     * @throws \ReflectionException
     * @throws \WangYu\exception\ReflexException
     */
    public function authority()
    {
        $result = (new AuthMap())->run();

        return $result;
    }

    /**
     * @doc('删除多个权限')
     * @auth('删除多个权限','管理员','hidden')
     * @route('remove','post')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "删除权限成功"
        }')
     * @error('{
        "code": 1001,
        "message": "参数验证 .   权限不能为空",
        "request_url": "cms\/admin\/remove"
        }')
     * @param Request $request
     * @param('group_id','分组id','require|integer|between:0,10000000000')
     * @param('auths','权限','require')
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function removeAuths(Request $request)
    {
        $params = $request->post();
        LinAuth::where(['group_id' => $params['group_id'], 'auth' => $params['auths']])
            ->delete();
        return writeJson(201, '', '删除权限成功');
    }

    /**
     * @doc('分配多个权限')
     * @auth('分配多个权限','管理员','hidden')
     * @route('/dispatch/patch','post')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "添加权限成功"
        }')
     * @error('{
        "code": 10000,
        "message": "请求未携带authorization信息",
        "request_url": "cms\/admin\/dispatch\/patch"
        }')
     * @param Request $request
     * @param('group_id','分组id','require|integer|between:0,10000000000')
     * @param('auths','权限','require')
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function dispatchAuths(Request $request)
    {
        $params = $request->post();

        LinAuth::dispatchAuths($params);
        Hook::listen('logger', '修改了id为' . $params['group_id'] . '的权限');
        return writeJson(201, '', '添加权限成功');
    }
}