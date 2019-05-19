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
     * @param $message
     * @throws LoggerException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function run($message)
    {

        // 行为逻辑
        if (empty($message)) {
            throw new LoggerException([
                'msg' => '日志信息不能为空'
            ]);
        }


        if (is_array($message)) {
            $uid = $message['uid'];
            $nickname = $message['nickname'];
        } else {
            $uid = Token::getCurrentUID();
            $nickname = Token::getCurrentName();
        }

        $params = [
            'message' => $nickname . $message,
            'user_id' => $uid,
            'user_name' => $nickname,
            'status_code' => Response::getCode(),
            'method' => Request::method(),
            'path' => Request::path(),
            'authority' => ''
        ];
        LinLog::create($params);
    }
}