<?php

namespace app\http\middleware;

use app\lib\auth\Auth as Permission;
use app\lib\exception\token\ForbiddenException;

class Auth
{
    /**
     * 权限验证
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \ReflectionException
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function handle($request, \Closure $next)
    {

        if ($request->path() === 'cms/user/login') {
            return $next($request);
        }

        $auth = (new Permission($request))->check();

        if (!$auth) {
            throw new ForbiddenException();
        }

        return $next($request);
    }
}
