# 简介

## 预防针

* 本项目非官方团队出品，仅出于学习、研究目的丰富下官方项目的语言支持。
* 局限于本人水平，有些地方还需重构，已经纳入了计划中，当然也会有我没考虑到的，希望有更多人参与进来一起完善，毕竟PHP作为世界上最好的语言不能缺席。

## 线上文档地址(完善中)

[https://chenjinchuang.github.io/](https://chenjinchuang.github.io/)

## 线上 Demo

TP5版的暂无，可直接参考官方团队的线上Demo：[http://face.cms.7yue.pro/](http://face.cms.7yue.pro/)

## 什么是 Lin CMS？

> Lin-CMS 是林间有风团队经过大量项目实践所提炼出的一套**内容管理系统框架**。Lin-CMS 可以有效的帮助开发者提高 CMS 的开发效率。

本项目是基于ThinkPHP 5.1的 Lin CMS 后端实现。

官方团队产品了解请访问[TaleLin](https://github.com/TaleLin)

## Lin CMS 的特点

Lin CMS 的构筑思想是有其自身特点的。下面我们阐述一些 Lin 的主要特点。

**Lin CMS 是一个前后端分离的 CMS 解决方案**

这意味着，Lin 既提供后台的支撑，也有一套对应的前端系统，当然双端分离的好处不仅仅在于此，我们会在后续提供NodeJS和PHP版本的 Lin。如果你心仪 Lin，却又因为技术栈的原因无法即可使用，没关系，我们会在后续提供更多的语言版本。为什么 Lin 要选择前后端分离的单页面架构呢？

首先，传统的网站开发更多的是采用服务端渲染的方式，需用使用一种模板语言在服务端完成页面渲染：比如 JinJa2、Jade 等。 服务端渲染的好处在于可以比较好的支持 SEO，但作为内部使用的 CMS 管理系统，SEO 并不重要。

但一个不可忽视的事实是，服务器渲染的页面到底是由前端开发者来完成，还是由服务器开发者来完成？其实都不太合适。现在已经没有多少前端开发者是了解这些服务端模板语言的，而服务器开发者本身是不太擅长开发页面的。那还是分开吧，前端用最熟悉的 Vue 写 JS 和 CSS，而服务器只关注自己的 API 即可。

其次，单页面应用程序的体验本身就要好于传统网站。

更多关于Lin CMS的介绍请访问[Lin CMS线上文档](http://doc.cms.7yue.pro/)

**框架本身已内置了 CMS 常用的功能**

Lin 已经内置了 CMS 中最为常见的需求：用户管理、权限管理、日志系统等。开发者只需要集中精力开发自己的 CMS 业务即可

## Lin CMS TP5 的特点

在当前项目的版本`(0.0.1)`中，特点更多来自于`ThinkPHP 5.1`框架本身带来的特点。通过充分利用框架的特性，实现高效的后端使用、开发，也就是说，只要你熟悉`ThinkPHP`框架，那么对于理解使用和二次开发本项目是没有难度的，即便对于框架的某些功能存在疑问也完全可以通过ThinkPHP官方的开发手册找到答案。当然我们更欢迎你通过[Issues](https://github.com/ChenJinchuang/lin-cms-tp5/issues)来向我们提问:)

在下一个版本中`(>0.0.1)`,我们会在框架的基础上融入一些自己的东西来增强或者优化框架的使用、开发体验。

## 所需基础

由于 Lin 采用的是前后端分离的架构，所以你至少需要熟悉 PHP 和 Vue。

Lin 的服务端框架是基于 ThinkPHP5.1的，所以如果你比较熟悉ThinkPHP的开发模式，那将可以更好的使用本项目。但如果你并不熟悉ThinkPHP，我们认为也没有太大的关系，因为框架本身已经提供了一套完整的开发机制，你只需要在框架下用 PHP 来编写自己的业务代码即可。照葫芦画瓢应该就是这种感觉。

但前端不同，前端还是需要开发者比较熟悉 Vue 的。但我想以 Vue 在国内的普及程度，绝大多数的开发者是没有问题的。这也正是我们选择 Vue 作为前端框架的原因。如果你喜欢 React Or Angular，那么加入我们，为 Lin 开发一个对应的版本吧。

# 快速开始

## Server 端必备环境

* 安装MySQL（version： 5.6+）

* 安装PHP环境(version： 7.1+)

## 获取工程项目

```bash
git clone https://github.com/ChenJinchuang/lin-cms-tp5.git
```

> 执行完毕后会生成lin-cms-tp5目录

## 安装依赖包

执行命令前请确保你已经安装了composer工具

```bash
cd lin-cms-tp5

composer install  // 如果长时间卡光标,请更换composer源或者挂梯子
```

## 数据库配置

Lin 需要你自己在 MySQL 中新建一个数据库，名字由你自己决定。例如，新建一个名为 lin-cms 的数据库。接着，我们需要在工程中进行一项简单的配置。使用编辑器打开 Lin 工程根目录下``/config/database.php``，找到如下配置项：

```php
// 服务器地址
  'hostname'        => '',
// 数据库名
  'database'        => 'lin-cms',
// 用户名
  'username'        => 'root',
// 密码
  'password'        => '',
  
  //省略后面一堆的配置项
```

**请务必根据自己的实际情况修改此配置项**

## 数据迁移

> 如果你已经部署过官方团队其他版本的Lin-cms后端，并且已经生成了相应基础数据库表，可以略过数据迁移章节，但必须将原来lin_user表中super记录删除(密码加密方式不一致，会导致登陆失败)，并在根目录下运行
```bash
php think seed:run  //这条命令会为你在lin_user表中插入一条记录,即super
```

配置完数据库连接信息后，我们需要为数据库导入一些核心的基础表，在项目根目录中，打开命令行，输入：

```bash
php think migrate:run
```

当你看到如下提示时，说明迁移脚本已经启动并在数据库中生成了相应的基础数据库表

```php
== 20190427113042 User: migrating
== 20190427113042 User: migrated 0.0540s

== 20190427125215 Book: migrating
== 20190427125215 Book: migrated 0.0593s

== 20190427125517 Image: migrating
== 20190427125517 Image: migrated 0.0557s

== 20190427125655 LinAuth: migrating
== 20190427125655 LinAuth: migrated 0.0721s

== 20190427125839 LinEvent: migrating
== 20190427125839 LinEvent: migrated 0.0648s

== 20190427125956 LinGroup: migrating
== 20190427125956 LinGroup: migrated 0.0656s

== 20190427130203 LinLog: migrating
== 20190427130203 LinLog: migrated 0.0558s

== 20190427130637 LinPoem: migrating
== 20190427130637 LinPoem: migrated 0.0879s

All Done. Took 0.6255s
```

迁移成功后我们需要为lin_user表插入一条数据，作为超级管理员，方便你后续在前端项目中登陆和测试，继续在命令行中输入：
```bash
php think seed:run
```
当你看到如下提示时，说明迁移脚本已经启动并在lin_user表中创建了一条记录

```php
== UserSeeder: seeding
== UserSeeder: seeded 0.0351s

All Done. Took 0.0385s
```

## 运行

如果前面的过程一切顺利，项目所需的准备工作就已经全部完成，这时候你就可以试着让工程运行起来了。在工程的根目录打开命令行，输入：
```bash
php think run //启动thinkPHP内置的Web服务器
```
```php
ThinkPHP Development server is started On <http://127.0.0.1:8000/>
You can exit with `CTRL-C`
```

打开浏览器，访问``http://127.0.0.1:8000``，你会看到一个欢迎界面，至此，Lin-cms-tp5部署完毕，可搭配[lin-cms-vue](https://github.com/TaleLin/lin-cms-vue)使用了。

## 注释验证器模式

> 参数说明见[注释验证器文档](https://github.com/china-wangyu/lin-cms-tp-validate-core)

### `第1步` 需要在`composer.json`引入`lin-cms-tp/validate-core`扩展（默认配置）

```json5
 // ....省略其它配置
 "require": {
     // ....省略其它扩展配置
    "lin-cms-tp/validate-core": "dev-master"
  },
 
```

### `第2步` 需要在命令行更新`composer.json`引入的`lin-cms-tp/validate-core`扩展

```bash
composer update
```

### `第3步:` 需要在中间件配置`config/middleware.php`中引入 `LinCmsTp\Param::class`（默认安装）

```php
return [
    // 默认中间件命名空间
    'default_namespace' => 'app\\http\\middleware\\',
    'ReflexValidate' => LinCmsTp\Param::class  // 开启注释验证器，需要的中间件配置，请勿胡乱关闭
];
```

### `第4步:` 需要在路由配置`route/route.php`中引入验证器中间件`ReflexValidate`

```php
use think\facade\Route;

Route::group('', function () {
   # .... 省略一大堆路由配置
})->middleware(['Auth','ReflexValidate'])->allowCrossDomain();
```

### `第5步:` 需要在方法注释中新增验证器`@validate('验证模型名称')`

> 本注释验证器模式有两种方式，如有不在`application\api\validate目录`的
> 验证器,请使用全命名空间，

>例如：`@validate('\app\common\validate\验证模 型名称')`

```php
    /**
     * 账户登陆
     * @param Request $request
     * @validate('LoginForm')
     * @return array
     * @throws \think\Exception
     */
    public function login(Request $request)
    {
//        (new LoginForm())->goCheck();  # 开启注释验证器以后，本行可以去掉，这里做更替说明
        # 省略部分逻辑，为了readme.md的维护性
    }
```

## 讨论交流
微信公众号搜索：林间有风
<br>
<img class="QR-img" src="http://imglf6.nosdn0.126.net/img/YUdIR2E3ME5weEdlNThuRmI4TFh3UWhiNmladWVoaTlXUXpicEFPa1F6czFNYkdmcWRIbGRRPT0.jpg?imageView&thumbnail=500x0&quality=96&stripmeta=0&type=jpg" width="150" height="150" style='text-align:left;width: 100px;height: 100px'>

QQ群搜索：Lin CMS 或 814597236

<img src="https://consumerminiaclprd01.blob.core.chinacloudapi.cn/miniappbackground/sfgmember/lin/qqgroup.jpg" width="150" height="205" >


## 下个版本开发计划

- [ ] 注解路由
- [x] 模型封装
- [x] 注解验证器