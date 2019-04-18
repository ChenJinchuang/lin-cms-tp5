<?php

namespace app\http\middleware;

use app\api\validate\group\CreateGroup as CreateGroupValidate;

class CreateGroup
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \app\lib\exception\ParameterException
     */
    public function handle($request, \Closure $next)
    {
        (new CreateGroupValidate())->goCheck();
        return $next($request);
    }
}
