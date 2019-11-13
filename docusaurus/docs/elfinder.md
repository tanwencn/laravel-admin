---
id: elfinder
title: 文件资源管理器
---
后台已经集成了```tanwencn/elfinder```，且已在```admin.php```中写好了默认配置。

## 配置驱动
```php
'elfinder' => [
        'default' => [
            'process' => Tanwencn\Admin\FinderProcess::class, //中间件
            'options' => [
                'disk' => 'public', //laravel驱动
                'uploadOverwrite' => false, //是否覆盖
                'uploadMaxSize' => '3M', //文件大小
                'onlyMimes' => ['image'], //文件格式
                'uploadOrder' => ['allow'], 
                'path' => 'images', //文件路径
                'alias' => 'Gallery' //驱动别名
            ]
        ]
    ],
```

## 简单使用
使用过程很简单，在```blade```中添加以下JS：
```javascript
Admin.boot(function () {
    Finder.disk().click('.select-image', '#avatar'); 
    //or Finder.disk('default').click('.select-image', '#avatar');
});
```
上方代码会在```.select-image```的点击事件中打开选择器，然后会把选中的图片地址作为值给到```#avatar```。

## 多选
```javascript
Admin.boot(function () {
    Finder.disk('default', {multiple:true})
    .click('.select-image', function(files){
        console.log(files);
    });
});
```
与单选不同的是，第二个参数接受闭包，同时会把值传入闭包，这在对多选和其它特殊处理过程中非常有用。