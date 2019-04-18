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
                ->findOrFail($uid);
        } catch (Exception $ex) {
            throw new UserException();
        }

        $auths = Auth::where('group_id', $user->group_id)
            ->field('group_id', true)
            ->select();

        $user = $user->toArray();
        $auths = split_modules($auths);
        $user['auths'] = $auths;

        return $user;
    }


    private static function checkPassword($md5Password, $password)
    {
        return $md5Password === md5($password);
    }

//    /**
//     * @return array|null|\PDOStatement|string|\think\Model
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\ModelNotFoundException
//     * @throws \think\exception\DbException
//     */
//    public static function getAllAccount()
//    {
//        $result = self::field('password', true)
//            ->with(['role' => function ($query) {
//                $query->with(['auth']);
//            }])
//            ->select();
//        return $result;
//    }
//
//    /**
//     * @param $params
//     * @throws UserException
//     */
//    public static function createAccount($params)
//    {
//        $params['password'] = md5($params['password']);
//        try {
//            self::create($params);
//        } catch (Exception $ex) {
//            throw new UserException([
//                'msg' => $ex->getMessage(),
//                'errorCode' => 20002
//            ]);
//        }
//
//    }
//
//    /**
//     * @param $uid
//     * @return array|null|\PDOStatement|string|\think\Model
//     * @throws \think\db\exception\DataNotFoundException
//     * @throws \think\db\exception\ModelNotFoundException
//     * @throws \think\exception\DbException
//     */
//    public static function getPersonageInfo($uid)
//    {
//        $user = self::where('id', '=', $uid)
//            ->field('password', true)
//            ->with(['role' => function ($query) {
//                $query->with(['auth']);
//            }])
//            ->find();
//        return $user;
//
//    }
//
//    /**
//     * @param $params
//     * @return boolean
//     * @throws UserException
//     */
//    public static function updateUserGroup($params)
//    {
//        $user = self::get($params['uid']);
//        if (!$user) {
//            throw new UserException([
//                'msg' => '所选账户不存在，刷新页面后重试'
//            ]);
//        }
//        $user->group_id = $params['groupId'];
//        return $user->save();
//    }
//
//
//    function role()
//    {
//        return $this->belongsTo('Group');
//    }
}