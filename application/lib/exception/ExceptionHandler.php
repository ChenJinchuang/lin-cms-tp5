<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/4/26
 * Time: 19:45
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $error_code;

    //需要返回客户端当前请求的URL路径

    /**
     * @param \Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            //如果是属于自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error_code = $e->error_code;
        } else {
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg = '服務器內部錯誤，不想告訴你';
                $this->error_code = 999;
                $this->recordErrorLog($e);
            }
        }

        $result = [
            'msg' => $this->msg,
            'error_code' => $this->error_code,
            'request_url' => Request::path()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => '',
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');

    }
}