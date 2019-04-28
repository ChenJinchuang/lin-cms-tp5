<?php
/**
 * Created by PhpStorm.
 * User: daogu
 * Date: 2017/6/1
 * Time: 22:19
 */

namespace app\lib\exception\token;


use app\lib\exception\BaseException;

class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg  = '权限不足，请联系管理员';
    public $error_code = 10002;
}