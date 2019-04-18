<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 9:54
 */

namespace app\lib\auth;

use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;

class Auth
{
    protected $request;

//
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * 主方法，拿到当前接口的权限内容，判断当前请求用户是否拥有这个权限。接口无权限标识或超级管理员直接通过
     * @return bool
     * @throws \ReflectionException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function check()
    {
        // 接口的权限内容
        $actionAuth = $this->actionAuth();
        // 账户信息，包含所拥有的权限列表
        $userAuth = $this->userAuth();

        // 如果这个方法没有添加权限标识，直接通过
        if (empty($actionAuth) || $userAuth['super'] == 1){
            return true;
        }

        // 生成账户拥有权限的数组
        $authList = [];
        foreach ($userAuth['role']['auth'] as $key => $value) {
            array_push($authList, $value['auth']);
        }

        // 判断接口权限是否在账户拥有权限数组内
        if (in_array($actionAuth, $authList)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    protected function actionAuth()
    {
        $controller = $this->request->controller();
        $action = $this->request->action();

        $class = new \ReflectionClass('app\\api\\controller\\' . $controller);
        // 获取指定方法的注释
        $actionDoc = $class->getMethod($action)->getDocComment();
        // 获取方法内的权限标识内容
        $actionAuth = (new AuthMap())->getMethodDoc($actionDoc);
        // $actionAuth返回的是一个数组，由于每个action只会有一个auth,
        //数组只会有一个元素，直接用current函数返回数组当前元素的值。
        return current($actionAuth);
    }

    /**
     * 获取账户信息
     * @return array
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    protected function userAuth()
    {
        $uid = TokenService::getCurrentUid();
        $user = UserModel::getPersonageInfo($uid);

        return $user->toArray();

    }
}