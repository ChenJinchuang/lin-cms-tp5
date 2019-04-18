<?php

namespace app\http\middleware;

use app\api\validate\user\CreateAccountValidate;
use app\lib\exception\user\UserException;


class CreateAccount
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \app\lib\exception\ParameterException
     * @throws UserException
     */
    public function handle($request, \Closure $next)
    {

        (new CreateAccountValidate())->goCheck();

        if ($request->post('group_id') == 1) {
            throw new UserException([
                'msg' => '不允许分配用户到超级管理员分组',
                'errCode' => 20002
            ]);
        }
        return $next($request);
    }
}
