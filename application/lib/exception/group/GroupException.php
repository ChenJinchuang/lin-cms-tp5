<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 16:43
 */

namespace app\lib\exception\group;


use app\lib\exception\BaseException;

class GroupException extends BaseException
{
    public $code = 400;
    public $msg  = '分组错误';
    public $errorCode  = 30000;
}