<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 12:49
 */

namespace app\api\model;


use app\lib\exception\group\GroupException;
use think\Db;
use think\Exception;

class Group extends BaseModel
{

    /**
     * @param $id
     * @return array|\PDOStatement|string|\think\Model
     * @throws GroupException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getGroupByID($id)
    {
        try {
            $group = self::findOrFail($id);
        } catch (Exception $ex) {
            throw new GroupException([
                'code' => 404,
                'msg' => '指定的分组不存在',
                'errorCode' => 30003
            ]);
        }
        $auths = Auth::where('group_id', $group->id)
            ->field('group_id', true)
            ->select();
        $group = $group->toArray();

        $group['auths'] = split_modules($auths->toArray());

        return $group;

    }

    /**
     * @param $params
     * @throws GroupException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public static function createGroup($params)
    {
        $group = self::where('name', $params['name'])->find();
        if ($group) {
            throw new GroupException([
                'errorCode' => 30004,
                'msg' => '分组已存在'
            ]);
        }

        Db::startTrans();
        try {
            $group = (new Group())->allowField(true)->create($params);

            $auths = [];
            foreach ($params['auths'] as $value) {
                $auth = findAuthModule($value);
                $auth['group_id'] = $group->id;
                array_push($auths, $auth);
            }

            (new Auth())->saveAll($auths);
            Db::commit();
        } catch (Exception $ex) {
            Db::rollback();
            throw new GroupException([
                'errorCode' => 30001,
                'msg' => '分组创建失败'
            ]);
        }
    }

}