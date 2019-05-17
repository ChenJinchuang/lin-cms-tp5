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
use think\Request;

/**
 * Class Admin
 * @middleware('Auth','linRouteParam')
 * @package app\api\controller\cms
 */
class Admin
{

    /**
     * @auth('查询所有用户','管理员')
     * @route('cms/admin/users','get')
     * @param Request $request
     * @param('group_id','分组ID','>:0')
     * @param('page','分页','require')
     * @param('count','条数','require')
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
     * @auth('修改用户密码','管理员')
     * @route('cms/admin/password/:uid','put')
     * @param Request $request
     * @param('uid','用户ID','require|>:0')
     * @param('new_password','新密码','require')
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
     * @auth('删除用户','管理员')
     * @route('cms/admin/:uid','delete')
     * @param $uid
     * @param('uid','用户ID','require|>:0')
     * @return \think\response\Json
     * @throws \LinCmsTp5\exception\user\UserException
     * @throws \think\Exception
     */
    public function deleteUser($uid)
    {
        LinUser::deleteUser($uid);

        logger('删除了用户id为' . $uid . '的用户');
        return writeJson(201, '', '操作成功');
    }

    /**
     * @auth('管理员更新用户信息','管理员')
     * @route('cms/admin/:uid','put')
     * @param $request
     * @param('uid','用户ID','require|>:0')
     * @param('email','邮箱','require|email')
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function updateUser(Request $request)
    {
        $params = $request->param();
        LinUser::updateUser($params);
        return writeJson(201, '', '操作成功');

    }

    /**
     * @auth('查询所有权限组','管理员')
     * @route('cms/admin/group/all','get')
     * @return mixed
     */
    public function getGroupAll()
    {
        $result = LinGroup::all();

        return $result;
    }

    /**
     * @auth('查询一个权限组及其权限','管理员')
     * @route('cms/admin/group/:id','get')
     * @param $id
     * @param('id','分组ID','require|>:0')
     * @return array|\PDOStatement|string|\think\Model
     * @throws \LinCmsTp5\admin\exception\group\GroupException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroup($id)
    {
        $result = LinGroup::getGroupByID($id);

        return $result;
    }


    /**
     * @auth('删除一个权限组','管理员')
     * @route('cms/admin/group/:id','delete')
     * @param $id
     * @param('id','分组ID','require|>:0')
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function deleteGroup($id)
    {
        LinGroup::destroy($id);

        logger('删除了权限组id为' . $id . '的权限组');
        return writeJson(201, '', '删除分组成功');
    }

    /**
     * @auth('新建权限组','管理员')
     * @route('cms/admin/group','post')
     * @param Request $request
     * @param('name','分组名称','require')
     * @param('auths','分组权限','require')
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\group\GroupException
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function createGroup(Request $request)
    {
        $params = $request->post();
        LinGroup::createGroup($params);

        return writeJson(201, '', '成功');
    }

    /**
     * @auth('更新一个权限组','管理员')
     * @route('cms/admin/group/:id','put')
     * @param Request $request
     * @param $id
     * @param('id','分组ID','require|min:1')
     * @param('name','分组名称','require')
     * @param('auths','分组权限','require')
     * @return \think\response\Json
     * @throws \think\Exception
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
     * @auth('查询所有可分配的权限','管理员')
     * @route('cms/admin/authority','get')
     * @throws \ReflectionException
     */
    public function authority()
    {
        $result = (new AuthMap())->run();

        return $result;
    }

    /**
     * @auth('删除多个权限','管理员')
     * @route('cms/admin/remove','post')
     * @param Request $request
     * @param('group_id','分组ID','require|min:1')
     * @param('auths','分组权限','require')
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
     * @auth('分配多个权限','管理员')
     * @route('cms/admin/dispatch/patch','post')
     * @param Request $request
     * @param('group_id','分组ID','require|min:1')
     * @param('auths','分组权限','require')
     * @return \think\response\Json
     * @throws \ReflectionException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function dispatchAuths(Request $request)
    {
        $params = $request->post();
        LinAuth::dispatchAuths($params);
        logger('修改了id为' . $params['group_id'] . '的权限');

        return writeJson(201, '', '添加权限成功');
    }
}