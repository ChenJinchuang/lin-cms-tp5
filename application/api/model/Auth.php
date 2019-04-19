<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 12:49
 */

namespace app\api\model;


class Auth extends BaseModel
{
    protected $hidden = ['id'];
    protected $table = 'lin_auth';


    /**
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAuthByGroupID($id)
    {
        $result = self::where('group_id', $id)
            ->field('group_id', true)
            ->select()->toArray();
        return $result;
    }
}