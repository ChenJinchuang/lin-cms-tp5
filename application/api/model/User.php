<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 11:22
 */

namespace app\api\model;


use app\lib\exception\user\UserException;
use think\Exception;

class User extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $hidden = ['delete_time', 'update_time'];
    protected $table = 'lin_user';


    /**
     * @param $nickname
     * @param $password
     * @return array|\PDOStatement|string|\think\Model
     * @throws UserException
     */
    public static function verify($nickname, $password)
    {
        try {
            $user = self::where('nickname', $nickname)->findOrFail();
        } catch (Exception $ex) {
            throw new UserException();
        }

        if (!self::checkPassword($user->password, $password)) {
            throw new UserException([
                'msg' => '密码错误，请重新输入',
                'errorCode' => 20001
            ]);
        }

        return $user;

    }

    /**
     * @param $uid
     * @return array|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws UserException
     */
    public static function getUserByUID($uid)
    {
        try {
            $user = self::field('password', true)
                ->findOrFail($uid)->toArray();
        } catch (Exception $ex) {
            throw new UserException();
        }

        $auths = Auth::getAuthByGroupID($user['group_id']);

        $auths = empty($auths) ? [] : split_modules($auths);

        $user['auths'] = $auths;

        return $user;
    }


    private static function checkPassword($md5Password, $password)
    {
        return $md5Password === md5($password);
    }

}