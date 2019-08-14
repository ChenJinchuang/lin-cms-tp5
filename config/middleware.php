<?php

return [
    // 默认中间件命名空间
    'default_namespace' => 'app\\http\\middleware\\',
    'ReflexValidate' => \WangYu\annotation\Validate::class  // 开启注释验证器，需要的中间件配置，请勿胡乱关闭
];
