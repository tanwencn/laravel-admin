---
id: config
title: 配置项
---

安装完成后，会生成一个```config/admin.php```配置文件，所有的配置项都能在这里找到。

## 布局

后台布局配置项，和前端相关的配置都在这里。

### layout.body_class
这个是```adminlte```的body标签的class属性值的配置。
- 固定：使用类```.fixed```获得固定的标题和侧边栏。
- 折叠的侧栏：使用类```.sidebar-collapse```在加载时具有折叠的侧栏。
- 盒装版式：使用类```.layout-boxed```获得仅延伸到1250px的盒装版式。
- 顶部导航使用类```.layout-top-nav```删除侧边栏，并使链接位于-顶部导航栏。

### layout.logo
这个是左上角显示的后台名称，可以配置Html内容，所以当然也可以是一个图片。

### layout.logo_mini
这个是菜单收缩起来后显示的名称。

### layout.footer
这个是主体页面的页脚内容，如果设置为```false```则会隐藏页脚。

## 路由
后台路由的配置项，和路由相关的配置都在这里。

### router.prefix
路由前缀

### router.namespaces
控制器的```namespaces```

### router.routes
路由文件的路径。这个文件中的路由命名会自动加上```admin.```前缀。如命名```$route->name('index')```，实际命名为```admin.index```。

### router.index
首页路由名称

## 中间件(router.middleware)
这里包含了后台路由所有的中间件，你的自定义中间件也在这里添加。

### Tanwencn\Admin\Http\Middleware\Authenticate
授权，如果你使用自定义授权，可以把这个删除。

### Tanwencn\Admin\Http\Middleware\Menu
注册默认菜单，你可以添加新的中间件注册自定义菜单，也可以直接替换这个。

### Tanwencn\Admin\Http\Middleware\Pjax
pjax内容过滤中间件，删除这个中间件，会自动取消pjax功能

### Tanwencn\Admin\Http\Middleware\Asset
因为```laravel-admin```使用Pjax功能，所以需要全局加载```css/js```资源，这里就是注册默认资源的地方。

### Tanwencn\Admin\Http\Middleware\HttpLog
写入后台操作日志的地方。

## 登录设置

### auth.login.controller
登录控制器，如若需要重写，可在这里写自定义控制器。

### auth.login.username
登录字段，默认为邮箱登录。更改此选项的同时，需要注意数据库必填字段及索引等结构。

### auth.login.default_password
新建后台用户的默认密码，在没有设置或填写密码的情况下生效。


## 授权(auth)
参考Laravel官方文档的config.auth设置项。

## 文件管理器(elfinder)

## 操作日志
后台操作日志配置。

### logger.method
```HttpLog```会只记录这里出现的```method```请求。

### logger.except
不需要记录的字段。

## Laravel Logs
后台查看Laravel 日志
### laravel_logs.read_once_rows
一页读取多少行日志
