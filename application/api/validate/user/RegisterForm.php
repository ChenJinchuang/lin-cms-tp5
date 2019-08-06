<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/28
 * Time: 21:38
 */

namespace app\api\validate\user;


use WangYu\validate\Validate;

class RegisterForm extends Validate
{
    protected $rule = [
        'password' => 'require|confirm:confirm_password',
        'confirm_password' => 'require',
        'nickname' => 'require|length:2,10',
        'group_id' => 'require|>:0',
        'email' => 'email'
    ];
}