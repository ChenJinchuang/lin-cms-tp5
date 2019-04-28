<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 16:22
 */

namespace app\api\validate\group;


use app\api\validate\BaseValidate;

class CreateGroup extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chs',
    ];

    protected $message = [
        'name.require' => '分组名不能为空',
        'name.chs' => '分组名必须是中文',
    ];
}