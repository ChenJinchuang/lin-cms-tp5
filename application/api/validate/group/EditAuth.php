<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 17:53
 */

namespace app\api\validate\group;


use app\api\validate\BaseValidate;

class EditAuth extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'auth' => 'require'
    ];

    protected $message = [
        'auth' => '权限内容不能为空'
    ];
}