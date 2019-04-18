<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/16
 * Time: 11:21
 */

namespace app\api\controller\cms;

use app\api\model\Group as GroupModel;
use app\lib\auth\AuthMap;
use app\lib\exception\group\GroupException;
use think\Request;

class Admin
{
    /**
     * @auth('查询所有权限组','管理员')
     * @return mixed
     */
    public function getGroupAll()
    {
        $result = GroupModel::all();

        return $result;
    }

    /**
     * @auth('查询一个权限组及其权限','管理员')
     * @param $id
     * @return array|\PDOStatement|string|\think\Model
     * @throws \app\lib\exception\group\GroupException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroup($id)
    {
        $result = GroupModel::getGroupByID($id);

        return $result;
    }


    /**
     * @auth('删除一个权限组','管理员')
     * @param $id
     * @return \think\response\Json
     */
    public function deleteGroup($id)
    {
        GroupModel::destroy($id);

        return writeJson(201, '','删除分组成功');
    }

    /**
     * @auth('新建权限组','管理员')
     * @param Request $request
     * @return \think\response\Json
     * @throws \ReflectionException
     * @throws \app\lib\exception\group\GroupException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function createGroup(Request $request)
    {
        $params = $request->post();
        GroupModel::createGroup($params);

        return writeJson(201, '', '成功');
    }

    /**
     * @auth('更新一个权限组','管理员')
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function updateGroup(Request $request, $id)
    {
        $params = $request->put();

        $group = GroupModel::find($id);
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
     * @throws \ReflectionException
     */
    public function authority()
    {
        $result = (new AuthMap())->run();

        return $result;
    }
}