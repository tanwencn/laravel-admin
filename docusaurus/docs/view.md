---
id: view
title: 视图
---

后台使用了```Laravel```的```Blade```模板引擎和```AdminLTE```模板框架做为布局和样式标准。

## 加载视图
视图的加载可以参考```Laravel```的官方文档。同时后台提供了简单的二次封装，常规情况下，你可以直接使用```Admin::view('index');```来进行视图加载。当然你也可以在控制器文档中找到另一种高度定制的加载方法。

## 布局示例

在视图渲染之前的任何地方注册，都会被视图加载出来，只要调用```Admin::asset()()->add()```即可。

如：在```admin.config.router.middleware```中添加```App\Admin\Middleware\Asset```中间件。

```php
@extends('admin::layouts.app') //继承布局

@section('title', $model->id?'添加':'修改')) //页面标题

@section('breadcrumbs') //面包屑
<li><a href="{{ Admin::action('index') }}"> 所有文章</a></li>
@endsection

@section('content')
内容

<script>
Admin.boot(function () {
    //视图局部js，在资源注册加载完毕后执行
    alert('ok');
});
</script>
@endsection
```

### 面包屑
上面的例子提供了一个面包屑选项，系统本身会默认用仪表盘做为起始面包屑，当前视图标题做为结束面包屑，比如此选项为空的情况下，默认生成的面包屑就为```仪表盘>添加文章```。
```blade
@section('breadcrumbs') //面包屑
<li><a href="{{ Admin::action('index') }}"> 所有文章</a></li>
@endsection
```
在视图中加上这段代码后，面包屑就为```仪表盘>所有文章>添加文章```。

### 局部JS
我们写常规代码时，大多会以```jquery```加载完成做为节点来写局部JS，如：
```js
$(function(){
    //js代码
});
```
但是这种写法会在```PJAX```下出现一些问题，所以在后台中，将使用新的初始化函数来编写JS局部代码：
```js
Admin.boot(function () {
    //js代码
});
```