<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/26
 * Time: 21:28
 */

namespace app\lib\logger;


class Logger
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return [
            $name, $arguments
        ];
    }
}