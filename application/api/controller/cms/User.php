<?php

namespace app\api\controller\cms;

use app\lib\token\Token;
use LinCmsTp5\admin\model\LinUser;
use think\Controller;
use think\facade\Hook;
use think\Request;

/**
 * Class User
 * @package app\api\controller\cms
 * @doc('管理用户类')
 * @group('cms/user')
 */
class User extends Controller
{
    /**
     * @doc('账户登陆')
     * @route('login','post')
     * @validate('LoginForm')
     * @success('{
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsaW4tY21zLXRwNSIsImlhdCI6MTU2NDk5MzkxMywiZXhwIjoxNTY1MDAxMTEzLCJ1c2VyIjp7ImlkIjoxLCJuaWNrbmFtZSI6InN1cGVyIiwiZW1haWwiOiIxMjM0NTZAcXEuY29tIiwiYWRtaW4iOjIsImFjdGl2ZSI6MSwiZ3JvdXBfaWQiOm51bGwsImNyZWF0ZV90aW1lIjoiMjAxOS0wOC0wNSAxNTo0NDo1NiIsImF2YXRhciI6bnVsbH19.XmDFaT-kKhI_sYHmhN2qtZYUoc4RrNMUZDm5zMQtIb0",
        "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsaW4tY21zLXRwNSIsImlhdCI6MTU2NDk5MzkxMywidXNlciI6eyJpZCI6MSwibmlja25hbWUiOiJzdXBlciIsImVtYWlsIjoiMTIzNDU2QHFxLmNvbSIsImFkbWluIjoyLCJhY3RpdmUiOjEsImdyb3VwX2lkIjpudWxsLCJjcmVhdGVfdGltZSI6IjIwMTktMDgtMDUgMTU6NDQ6NTYiLCJhdmF0YXIiOm51bGx9fQ.otFzRXoaSWqlbIhGiQ1vgOoOb6H309P4V3rjLAa-QiQ"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 参数验证 .   用户名不能为空,密码不能为空",
        "request_url": "cms\/user\/login"
        }')
     * @param Request $request
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     */
    public function login(Request $request)
    {
        $params = $request->post();
        $user = LinUser::verify($params['nickname'], $params['password']);
        $result = Token::getToken($user);
        Hook::listen('logger', array(
            'uid' => $user->id,
            'nickname' => $user->nickname,
            'msg' => '登陆成功获取了令牌'
        ));
        return $result;
    }


    /**
     * @doc('查询自己拥有的权限')
     * @route('auths','get')
     * @success('{
        "id": 1,
        "nickname": "super",
        "email": "123456@qq.com",
        "admin": 2,
        "active": 1,
        "group_id": null,
        "create_time": "2019-08-05 15:44:56",
        "avatar": null,
        "auths": []
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 请求未携带authorization信息",
        "request_url": "cms\/user\/auths"
        }')
     * @return array|\PDOStatement|string|\think\Model
     * @throws \LinCmsTp5\admin\exception\user\UserException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllowedApis()
    {
        $uid = Token::getCurrentUID();
        $result = LinUser::getUserByUID($uid);
        return $result;
    }

    /**
     * @doc('创建用户')
     * @auth('创建用户','管理员','hidden')
     * @route('register','post')
     * @validate('RegisterForm')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "用户创建成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 用户名重复，请重新输入",
        "request_url": "cms\/user\/register"
        }')
     * @param Request $request
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $params = $request->post();
        LinUser::createUser($params);

        Hook::listen('logger', '创建了一个用户');

        return writeJson(201, '', '用户创建成功');
    }

    /**
     * @doc('获取信息')
     * @route('information','get')
     * @success('{
        "id": 1,
        "nickname": "super",
        "email": "123456@qq.com",
        "admin": 2,
        "active": 1,
        "group_id": null,
        "create_time": "2019-08-05 15:44:56",
        "avatar": null
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 请求未携带authorization信息",
        "request_url": "cms\/user\/information"
        }')
     * @return mixed
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function getInformation()
    {
        $user = Token::getCurrentUser();
        return $user;
    }

    /**
     * @doc('设置头像')
     * @route('avatar','put')
     * @success('{
        "error_code": 0,
        "result": "",
        "msg": "更新头像成功"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . 1000: 错误内容 . 请求未携带authorization信息",
        "request_url": "cms\/user\/avatar"
        }')
     * @param('url','头像url','require|url')
     * @return \think\response\Json
     * @throws \LinCmsTp5\admin\exception\user\UserException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function setAvatar(Request $request)
    {
        $url = $request->put('avatar');
        $uid = Token::getCurrentUID();
        LinUser::updateUserAvatar($uid, $url);

        return writeJson(201, '', '更新头像成功');
    }


    /**
     * @doc('刷新token')
     * @route('refresh','get')
     * @success('{
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsaW4tY21zLXRwNSIsImlhdCI6MTU2NDk5NzkyMiwiZXhwIjoxNTY1MDA1MTIyLCJ1c2VyIjp7ImlkIjoxLCJuaWNrbmFtZSI6InN1cGVyIiwiZW1haWwiOiIxMjM0NTZAcXEuY29tIiwiYWRtaW4iOjIsImFjdGl2ZSI6MSwiZ3JvdXBfaWQiOm51bGwsImNyZWF0ZV90aW1lIjoiMjAxOS0wOC0wNSAxNTo0NDo1NiIsImF2YXRhciI6bnVsbH19.VAQLm_0XZzD4N-3nW7346rCeERXBUrRwe4L8CkSC7sM"
        }')
     * @error('{
        "code": 400,
        "message": "3000: 错误内容 . token已经过期",
        "request_url": "cms\/user\/refresh"
        }')
     * @return array
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function refresh()
    {
        $result = Token::refreshToken();
        return $result;
    }

}
