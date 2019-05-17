<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/5/16
 * Time: 17:19
 */

namespace app\api\behavior;


use app\lib\token\Token;
use LinCmsTp5\admin\exception\logger\LoggerException;
use LinCmsTp5\admin\model\LinLog;
use think\facade\Request;
use think\facade\Response;

class Logger
{
    /**
     * @param $params
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function run($message)
    {

        // 行为逻辑
        if ($message === '') {
            throw new LoggerException([
                'msg' => '日志信息不能为空'
            ]);
        }
        $nickname = Token::getCurrentName();

        $params = [
            'message' => $nickname ? $nickname . $message : Token::getCurrentName() . $message,
            'user_id' => Token::getCurrentUID(),
            'user_name' => $nickname,
            'status_code' => Response::getCode(),
            'method' => Request::method(),
            'path' => Request::path(),
            'authority' => ''
        ];
        LinLog::create($params);
    }
}