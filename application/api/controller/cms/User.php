<?php

namespace app\api\controller\cms;

use app\lib\token\Token;
use think\Controller;
use think\Request;
use app\api\model\User as UserModel;

class User extends Controller
{
    /**
     * 账户登陆
     * @param Request $request
     * @return array
     * @throws \think\Exception
     */
    public function login(Request $request)
    {
        $params = $request->post();
        $user = UserModel::verify($params['nickname'], $params['password']);
        // TODO 记录日志
        $result = Token::getToken($user);
        return $result;
    }


    /**
     * 查询自己拥有的权限
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function getAllowedApis()
    {
        $uid = Token::getCurrentUID();
        $result = UserModel::getUserByUID($uid);
        return $result;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\user\UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $params = $request->post();
        UserModel::createUser($params);
        return writeJson(201, '', '用户创建成功');
    }

}
