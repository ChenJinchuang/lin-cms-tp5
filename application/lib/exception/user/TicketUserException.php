<?php
/**
 * Created by PhpStorm.
 * User: daogu
 * Date: 2017/5/29
 * Time: 23:50
 */

namespace app\lib\exception\user;


use app\lib\exception\BaseException;

class TicketUserException extends BaseException
{
    public $code = 404;
    public $msg  = '用户不存在';
    public $errorCode = '20000';
}