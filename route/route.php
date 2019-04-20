<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::group('cms', function () {

    // 账户相关接口分组
    Route::group('user', function () {
        // 登陆接口
        Route::post('login', 'api/cms.User/login')
            ->middleware('Login');
        // 查询自己拥有的权限
        Route::get('auths', 'api/cms.User/getAllowedApis');
    });
    // 管理类接口
    Route::group('admin', function () {
        // 查询所有权限组
        Route::get('group/all', 'api/cms.Admin/getGroupAll');
        // 查询一个权限组及其权限
        Route::get('group/:id', 'api/cms.Admin/getGroup');
        // 删除一个权限组
        Route::delete('group/:id', 'api/cms.Admin/deleteGroup');
        // 更新一个权限组
        Route::put('group/:id', 'api/cms.Admin/updateGroup');
        // 新建权限组
        Route::post('group', 'api/cms.Admin/createGroup');
        // 查询所有可分配的权限
        Route::get('authority', 'api/cms.Admin/authority');
        // 删除多个权限
        Route::post('remove', 'api/cms.Admin/removeAuths');

    });
})
    ->allowCrossDomain();