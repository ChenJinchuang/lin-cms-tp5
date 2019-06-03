<?php
/**
 * Created by PhpStorm.
 */

namespace app\lib\exception\token;


use LinCmsTp5\exception\BaseException;

class DeployException extends BaseException
{
    public $code = 500;
    public $msg  = '请修改服务器环境配置为：opcache.save_comments=1';
    public $error_code = 50000;
}
