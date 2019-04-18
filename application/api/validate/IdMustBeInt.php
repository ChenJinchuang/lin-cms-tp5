<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 17:05
 */

namespace app\api\validate;


class IdMustBeInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
    ];
}