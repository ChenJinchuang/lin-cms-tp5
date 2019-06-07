<?php
/**
 * Created by PhpStorm.
 * Author: WZS
 * User: 17732
 * Date: 2019/6/7
 * Time: 18:36
 */

namespace app\api\validate\user;


use LinCmsTp5\validate\BaseValidate;

class UpdateAvatar extends BaseValidate
{
    protected $rule = [
        'url' => 'require|url'
    ];
}