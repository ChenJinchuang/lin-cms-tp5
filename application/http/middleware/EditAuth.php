<?php

namespace app\http\middleware;

use app\api\validate\group\EditAuth as EditAuthValidate;
use app\lib\auth\AuthMap;

class EditAuth
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \ReflectionException
     * @throws \app\lib\exception\ParameterException
     */
    public function handle($request, \Closure $next)
    {
        (new EditAuthValidate)->goCheck();
        $authParams = $request->patch('auth');
        $request->auths = $this->handleAuthParams($authParams);
        return $next($request);
    }

    /**
     * @param $params
     * @return array
     * @throws \ReflectionException
     */
    function handleAuthParams($params)
    {
        $result = [];
        $authMap = (new AuthMap())->run();
        foreach ($authMap as $key => $value) {
            foreach ($params as $k => $v) {
                if (in_array($v, $value)) {

                    $item = [
                        'auth' => $v,
                        'module' => $key
                    ];
                    array_push($result, $item);
                }
            }
        }
        return $result;
    }

}
