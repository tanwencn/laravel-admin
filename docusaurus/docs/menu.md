---
id: menu
title: 菜单
---

为了方便自定义管理，需要开发者在代码中对菜单进行添加和修改。

## 注册菜单示例

在视图渲染之前的任何地方注册，都会被视图加载出来，只要调用```Admin::menu()->new('添加菜单')```即可。

如：在```admin.config.router.middleware```中添加```App\Admin\Middleware\Menu```中间件。

```php
public function handle($request, Closure $next)
    {
        Admin::menu()->new('<i class="fa fa-weixin"></i> <span>一级菜单</span>')
            ->child('二级菜单', function (Item $menu) {
                $menu->route('admin.menu')->auth('二级菜单所需要的权限');
            })
        Admin::menu()->new('<i class="fa fa-weixin"></i> <span>一级菜单</span>')
            ->child('二级菜单2', function (Item $menu) {
                $menu->route('admin.menu');
                $menu->child('三级菜单')->auth('三级显示所需要的权限');
            })
        ;

        return $next($request);
    }
```
以上就是菜单注册的全过程，```Admin::menu()->new()```会返回一个新的菜单对象，这个对象的所有函数都会返回当前菜单。```$menu->child()```类似于```new()```，不同在地方在于```child()```不会返回给你子菜单，子菜单的所有操作都在闭包中进行。

## 可用方法

### 注册新菜单
```$menu = Admin::menu()->new($title, $sort=10)```会返回一个新的菜单对象。

### 添加子菜单
```$menu->child($title, $closure)```会在当前菜单对象中注册一个子菜单，并在闭包中返回。

### 菜单显示权限
```$menu->auth($authority)```会根据是否具有```$authority```权限来进行显示判断。

### 链接

#### 路由
```$menu->route($name, $parameters = [], $absolute = true)```用路由生成菜单。

#### 相对链接
```$menu->uri($uri)```用相对链接生成菜单。

#### 绝对链接
```$menu->url($url)```用绝对链接生成菜单。

#### 新窗口打开菜单
```$menu->url($url)->blank()```新窗口打开外部链接。