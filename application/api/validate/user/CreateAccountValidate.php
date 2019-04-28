<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 13:21
 */

namespace app\api\validate\user;


use app\api\validate\BaseValidate;

class CreateAccountValidate extends BaseValidate
{
    protected $rule = [
        'account' => 'require|alphaNum|length:6,16',
        'name'=>'require|isNotEmpty|chs',
        'password' => 'require|length:6,16|alphaNum'
    ];

    protected $message = [
        'account' => '用户名不能为空',
        'account.alphaNum' => '用户名必须是字母或数字组合',
        'account.length'=>'用户名长度必须为6-16位',
        'name.chs'=>'真实姓名必须是汉字',
        'name.require'=>'真实姓名不能为空',
        'password' => '密码不能为空',
        'password.length'=>'密码长度必须为6-16位',
        'password.alphaNum'=>'密码只能是数字或字母'
    ];
}