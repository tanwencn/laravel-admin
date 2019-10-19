---
id: view
title: 视图
---

后台使用了```Laravel```的```Blade```模板引擎和```AdminLTE```模板框架做为布局和样式标准。

## 加载视图
视图的加载可以参考```Laravel```的官方文档。同时后台提供了简单的二次封装，常规情况下，你可以直接使用```Admin::view('index/dashboard');```来进行视图加载。

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

## 视图重写
如果你需要重写扩展包的视图，比如想自定义一个登录页面，可以按照以下方法进行操作。

### 1.复制视图文件到指定位置
复制```/vendor/tanwencn/laravel-admin/resources/views```下的所有文件和目录到```/resources/views/vendor/admin```目录。

###  2.更改登录页的模板文件
打开登录页模板文件：```/resources/views/vendor/admin/_auth/login.blade.php```，在此对其进行修改完成后保存即可。

## 表格
```blade
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th class="table-select"></th>
            <th>名称</th>
            <th>性别</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $row)
        <tr>
            <td class="table-select">{{ $row->id }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->sex }}</td>
            <td> @can('edit_user') <a href="{{ Admin::action('edit', $role->id) }}">{{ trans('admin.edit') }}</a> &nbsp; @endcan @can('delete_role') <a href="javascript:void(0);" data-url="{{ route('admin.users.destroy', $role->id) }}" class="grid-row-delete">{{ trans('admin.delete') }}</a> @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```
以上是一段列表的```table```输出示例。但是又和常规的有所不同。

#### 滑动条
```.table```自动开启了固定高度，如果你的表格太宽，需要固定宽度，则需要对```table```标签添加```data-scroll-y="true"```属性。

如果你不需要这些，或者需要对使用自定义的```datable```，则可以在```class```中添加```.no-data```类进行关闭。

#### 全选按钮
在表格的```thead>td```项添加```table-select```则会自动转化为全选按钮。而```tbody>td```中添加```table-select```并在标签里写上```id```则会生成一个类名为```grid-row-checkbox```的单选按钮。

#### 获取全选项
在```javascrip```中使用```Admin.listSelectedRows()```函数可以获取当前视图中含有```.grid-row-checkbox```类的选中项的值。

## 提示信息
后台会自动用```toast```显示表单错误信息以及```withErrors()```内容，同时会显示```with('toastr_success', 'ok')```成功信息。

后台集成了```jquery confirm```和```toast```插件，有需要可以自行搜索。