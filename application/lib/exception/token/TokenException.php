<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/26
 * Time: 23:23
 */

namespace app\lib\exception\token;


use app\lib\exception\BaseException;

class TokenException extends BaseException
{
    public $code = 401;
    public $msg  = 'Token已过期或无效Token';
    public $errorCode = '10001';
}