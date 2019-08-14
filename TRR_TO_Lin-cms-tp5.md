# TRR 重写 Lin-cms-tp5

## `@step1` 更换扩展


### 去掉 `lin-cms-tp/validate-core` 

```bash
composer remove lin-cms-tp/validate-core
```

### 采用  `wangyu/tp-anntation`

```bash
composer remove wangyu/tp-anntation
```

## `@step2` 配置注解验证器中间件以及`lin-cms-tp5`的`auth`中间件

> 文件位置：`config/middleware.php`

内容：

```php
return [
    // 默认中间件命名空间
    'default_namespace' => 'app\\http\\middleware\\',
    'ReflexValidate' => \WangYu\annotation\Validate::class  // 开启注释验证器，需要的中间件配置，请勿胡乱关闭
];
```


## `@step3` 注册注解路由

> 操作文件在 `route/route.php`

### 普通路由

```php
use think\facade\Route;

Route::group('', function () {
    Route::group('cms', function () {
        // 账户相关接口分组
        Route::group('user', function () {
            // 登陆接口
            Route::post('login', 'api/cms.User/login');
            // 刷新令牌
            Route::get('refresh', 'api/cms.User/refresh');
            // 查询自己拥有的权限
            Route::get('auths', 'api/cms.User/getAllowedApis');
            // 注册一个用户
            Route::post('register', 'api/cms.User/register');
            // 更新头像
            Route::put('avatar','api/cms.User/setAvatar');
            // 查询自己信息
            Route::get('information','api/cms.User/getInformation');
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
            // 添加多个权限
            Route::post('/dispatch/patch', 'api/cms.Admin/dispatchAuths');
            // 查询所有用户
            Route::get('users', 'api/cms.Admin/getAdminUsers');
            // 修改用户密码
            Route::put('password/:uid', 'api/cms.Admin/changeUserPassword');
            // 删除用户
            Route::delete(':uid', 'api/cms.Admin/deleteUser');
            // 更新用户信息
            Route::put(':uid', 'api/cms.Admin/updateUser');

        });
        // 日志类接口
        Route::get('log', 'api/cms.Log/getLogs');
        Route::get('log/users', 'api/cms.Log/getUsers');
        Route::get('log/search', 'api/cms.Log/getUserLogs');

        //上传文件类接口
        Route::post('file/','api/cms.File/postFile');
    });
    Route::group('v1', function () {
        // 查询所有图书
        Route::get('book/', 'api/v1.Book/getBooks');
        // 新建图书
        Route::post('book/', 'api/v1.Book/create');
        // 查询指定bid的图书
        Route::get('book/:bid', 'api/v1.Book/getBook');
        // 搜索图书

        // 更新图书
        Route::put('book/:bid', 'api/v1.Book/update');
        // 删除图书
        Route::delete('book/:bid', 'api/v1.Book/delete');
    });
})->middleware(['Auth','ReflexValidate'])->allowCrossDomain();
```



### 注解路由

> \WangYu\annotation\Route 基础于 \think\facade\Route .所以tp内置路由也可以通过这个设置

```php

# 注册`api`模块的路由，并且设置两个全局中间件 'Auth','ReflexValidate'
\WangYu\annotation\Route::reflex('api',['Auth','ReflexValidate']);

# 注册单个路由，采用tp的get方法
\WangYu\annotation\Route::get('apiShow','index/index/apiMdDemo');
```

当然，这还没有完，请耐心看完文档，了解操作。


## `@step4` 配置注解路由

### 注解参数说明

| 名称 | 作用 | 类 | 方法 | 形态 | 举例 |
|:----:|:----:|:----:|:----:|:----:|:----:|
| doc | `类`/`方法`功能描述 | ☑ | ☑ | @doc('`类`/`方法`功能描述')|@doc('创建图书') |
| middleware | `类`/`方法`中间件注册 | ☑ | ☑ | @middleware('中间件名称1',...) | @middleware('Validate') |
| group | `类`路由分组 | ☑ |  ️ | @group('分组rule') | @group('v1/book') |
| route | `方法`路由注册, <br> 如果存在类路由分组`@group`注解函数， <br> 就会把 方法`@route`路由`rule`, <br> 拼接到类`@group`路由分组`rule`之后，<br>并用`/`链接 | ️ | ☑ | @route('rule','method') | @route('create','post') |

> 使用文档：[点我🔥](https://china-wangyu.github.io/views/php/trr/v0.0.2/%E8%B7%AF%E7%94%B1/)

因为涉及的类和接口有很多，这里举例说明

举例对象： `application/api/controller/v1/Book.php`

- 类注解路由

    例：
    
    ```php
    /**
    * Class Book
    * @doc('图书类')  # 👈这个地方，是在写类简介，告诉别人干嘛的
    * @group('v1/book') # 👈 这个是在，注册类路由分组，下面方法的路由就会先拼接类路由，等同tp的group
    * ### @middleware # 👈 这个，类没有专属的中间件操作，所以这个中间件注册也不需要
    * @package app\api\controller\v1
    */
    class Book{}
    
    ```

- 方法注解路由

    例：`getBook`方法
    
    ```php
    /**
     * @doc('查询指定bid的图书') # 👈这个地方，是在写方法简介，告诉别人干嘛的
     * @route(':bid','get') # 👈这个地方，注解方法路由，代表这个方法需要通过get方式访问，必须传递bid这个值，等同于Route::get('v1/book/:bid')
     * #@middleware() # 👈 注解方法中间件，代表这个中间件就只有这个方法使用 
     * #@validate() # 👈 注解方法验证器模型，代表这个方法使用`api/validate/`目录下的验证器模型，本方法没用
     * @success('{
             "id": 1,
             "title": "12",
             "author": "21",
             "summary": "123",
             "image": "212",
             "create_time": "2019-08-07 11:54:22",
             "update_time": "2019-08-07 12:01:23",
             "delete_time": null
             }') # 👈 成功返回的json举例,可以多行，只要别加前面的*号，可以为空
     * @error('')  # 👈 失败返回的json举例,可以多行，只要别加前面的*号，可以为空
     * @param Request $bid
     * @param('bid','bid的图书','require') # 👈 注解参数验证，代表必须传递参数 bid ，否则返回一个参数验证错误
     * @return mixed
     */
    public function getBook($bid)
    {
        $result = BookModel::get($bid);
        return $result;
    }
    ```
    
    
## `@step5` API 文档生成 `think` 命令


### 注册
 
> 文件在：application/command.php

内容：

```php

<?php
return [
    "lin:doc" => \WangYu\annotation\DocCommand::class
];
```

### 参考命令帮助

```bash
wy@aokodeiMac lin-cms-tp5 (fix/base_revise) $ php think lin:doc -h
Usage:
  doc:build [options]

Options:
      --module=MODULE   your API Folder,Examples: api = /application/api [default: "api"]
      --type=TYPE       your API file type,type = html or markdown [default: "html"]
      --name=NAME       your API filename [default: "api-doc"]
      --force=FORCE     your API filename is exist, backup and create, force = true or false [default: true]
  -h, --help            Display this help message
  -V, --version         Display this console version
  -q, --quiet           Do not output any message
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```


### 生成文档命令

默认命令，生成`html`格式的文档

```bash
php think lin:doc
```

## `@step6` 修改异常捕获

> http异常配置在 ：config/app.php

修改 `exception_handle` 选项为： `\WangYu\exception\TpHttpException::class`,


## `@step7` 修改下 lin-cms-tp5 的异常基类

> 文件位置：`vendor/lin-cms-tp5/base-core/src/exception/BaseException.php`

继承于 `\WangYu\exception\Exception`

```php
public function __construct($params = [])
{
    if (!is_array($params)) {
        return;
    }
    if (array_key_exists('code', $params)) {
        $this->code = $params['code'];
    }
    if (array_key_exists('msg', $params)) {
        $this->msg = $params['msg'];
    }
    if (array_key_exists('error_code', $params)) {
        $this->error_code = $params['error_code'];
    }
    $this->user_code = $this->error_code;
    parent::__construct($this->msg);
}
```


##  `@step8` 修改下 lin-cms-tp5 的`@auth`权限获取操作

> 操作文件：`application/lib/auth/AuthMap.php`

旧方法： `getMethodsDoc` 


新方法： `newGetMethodsDoc`

这个文件的操作我都有备注。