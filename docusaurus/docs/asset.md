---
id: asset
title: CSS/JS资源
---

因为使用了Pjax进行页面切换，所以后台的css/js资源需要集中注册，为此提供了以下方法。

## 注册全局资源文件

在视图渲染之前的任何地方注册，都会被视图加载出来，只要调用```Admin::asset()()->add()```即可。

如：在```admin.config.router.middleware```中添加```App\Admin\Middleware\Asset```中间件。

```php
public function handle($request, Closure $next)
{
    Admin::asset()->add('app.css')
    Admin::asset()->add('app.js')

    return $next($request);
}
```
以上就完成简单的注册。

### add($path)
注册一个资源，```path```可以是一个绝对路径也可以是一个相对路径，相对路径的最终生成路径参考```Laravel```中的```asset()```函数。

### addBag($path)
此函数等于```add()```，不同之处在于```addBag()```会分别注册一个```css```和一个```js```。如：
```php
Admin::asset()->addBag('app')
```
以上就会分别注册一个```app.css```和一个```app.js```。