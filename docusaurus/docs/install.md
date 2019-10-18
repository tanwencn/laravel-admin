---
id: install
title: 安装使用
---

安装过程非常简单，只需以下几个步骤。

## 安装前
### 运行环境
- ```Laravel >= 5.5```

## 开始安装
### 1.在laravel项目根目录下，通过 Composer 安装扩展包
```composer
composer require tanwencn/laravel-admin
```

### 2.修改配置文件
数据库存信息，在```.env``` 设置数据库连接信息

本地化，在 ```config/app.php```中设置```'locale' => 'zh-CN'```

### 3.执行安装命令
```php
php artisan admin:install
```

### 4.安装完成

打开链接：http://youwebsite/admin

输入登录账号：admin@admin.com   
输入登录密码：admin

### 5.生成自定义开发目录
你可以基于```Laravel```的规则，编写任何你想要的目录结构进行后台开发，也可以使用```php artisan admin:dir```生成如下目录进行后台开发：
```
App
└───Admin
    │   routes.php
│   └───Controllers
│       │   ...
│   
└───Http
    ...
```

### 6.添加自定义视图目录
```php artisan admin:dir```这个命令只是简单的帮你了生成了```routes.php```和```controllers```目录，你也可以在```App\Admin```下手动创建```views```目录作为后台视图目录。
```
App
└───Admin
    │   routes.php
│   └───Controllers
│   └───Views
│       │   ...
│   
└───Http
    ...
```
然后需要在```App\Providers\AppServiceProvider```目录中添加如下代码：
```
public function boot()
{
    $this->loadViewsFrom(app_path('Admin/Views'), 'admin');
}
```