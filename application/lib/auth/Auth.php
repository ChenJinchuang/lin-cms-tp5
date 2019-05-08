<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 9:54
 */

namespace app\lib\auth;

use app\lib\token\Token;
use LinCmsTp5\model\LinUser;

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
        // 如果这个接口没有添加权限标识，直接通过
        if (empty($actionAuth)) {
            return true;
        }
        // 接口的权限内容
        $actionAuth = $this->actionAuth();
        // 账户信息，包含所拥有的权限列表
        $userAuth = $this->userAuth();
        //账户属于超级管理员，直接通过
        if ($userAuth['admin'] == 2) {
            return true;
        }

        // 生成账户拥有权限的数组
        $authList = [];
        foreach ($userAuth['auths'] as $key => $value) {
            foreach ($value as $k => $v) {
                foreach ($v as $auth) {
                    array_push($authList, $auth['auth']);
                }
            }
        }

        // 判断接口权限是否在账户拥有权限数组内
        if (in_array(key($actionAuth), $authList)) {
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
        // 获取当前请求的控制层
        $controller = $this->request->controller();
        // 控制层下有二级目录，需要解析下。如controller/cms/Admin，获取到的是Cms.Admin
        $controllerPath = explode('.', $controller);
        // 获取当前请求的方法
        $action = $this->request->action();
        // 反射获取当前请求的控制器类
        $class = new \ReflectionClass('app\\api\\controller\\' . $controllerPath[0] . '\\' . $controllerPath[1]);
        // 获取控制器类下指定方法的注释
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
        $uid = Token::getCurrentUID();
        $user = LinUser::getUserByUID($uid);

        return $user;

    }
}