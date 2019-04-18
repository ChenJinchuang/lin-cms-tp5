<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 12:06
 */

namespace app\api\validate\user;


use app\api\validate\BaseValidate;

class LoginValidate extends BaseValidate
{
    protected $rule = [
        'nickname' => 'require|isNotEmpty',
        'password' => 'require|isNotEmpty'
    ];

    protected $message = [
        'nickname' => '用户名不能为空',
        'password' => '密码不能为空'
    ];
}