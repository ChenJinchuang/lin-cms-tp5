
# 更新日志

## 0.0.1 (2019-06-12)

* 新增文件上传接口

* 新增头像更新接口

> ps:旧版本更新需再执行一次数据库迁移脚本命令`php think migrate:run`,用于新增lin_file表和lin_user表新增avatar字段

## 0.0.1 (2019-05-21)

* 注解验证器实现

## 0.0.1 (2019-05-19)

* 修复前端分页查询适配问题
* 改变行为日志记录实现方式为Hook

## 0.0.1（2019-05-15）

* 依赖包调整
* 同步示例代码引用类库的命名空间
* 修复一些bug

## 0.0.1（2019-04-29）

* 初始化内测版

## 0.0.1-ref-reflect (2019-08-18) 嗝嗝

* 去掉composer的`lin-cms-tp/validate-core`依赖
* 新增composer的`wangyu/tp-anntation`依赖
* 新增注解路由功能，[参考文档](https://china-wangyu.github.io/views/php/trr/v0.0.2/路由/)
* 新增注解接口文档功能,[参考文档](https://china-wangyu.github.io/views/php/trr/v0.0.2/API%E6%96%87%E6%A1%A3/)
    ```bash
    # 命令
    php think lin:doc
    ```
* 更替注解验证器（使用方式不变）,[参考文档](https://china-wangyu.github.io/views/php/trr/v0.0.2/验证器/)
* 更新注解权限功能
* 新增api文档访问路由配置，访问地址：`http://域名:端口/apiShow`
