<?php

namespace app\http\middleware;

use app\api\validate\user\LoginValidate;

class Login
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \app\lib\exception\ParameterException
     */
    public function handle($request, \Closure $next)
    {
        (new LoginValidate())->goCheck();
        return $next($request);
    }
}
