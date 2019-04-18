<?php

namespace app\http\middleware;

use app\api\service\Token as TokenService;
use app\lib\exception\token\TokenException;
use think\Exception;

class Logout
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws TokenException
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->header('token');
        if (empty($token)) {
            throw new TokenException([
                'code' => 400,
                'msg' => '当前账户令牌获取失败，请刷新页面',
                'errorCode' => 10003
            ]);
        }
        return $next($request);

    }
}
