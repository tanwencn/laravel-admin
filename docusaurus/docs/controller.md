---
id: controller
title: 控制器
---

后台提供了一个父类控制器，封装了一些常用方法，如视图加载和权限判断。

## 使用方法
```php
use Tanwencn\Admin\Http\Controllers\Controller;

class CustomizeController extends Controller
{

}
```

## 加载视图
```php
class CustomizeController extends Controller
{
    public function index(){
        $data = [];
        $this->view('index', compact('data'));
    }
}
```
以上方法会自动加载后台视图目录中的```customizes```文件夹中的```index.blade.php```文件。
这个函数的加载逻辑就是以控制器名为复数形式的视图目录，如果你不喜欢这个小功能，你可以使用```Admin::view()```函数来加载视图。

### 权限
判断后台权限是件很自由的事情，你可以按照```Laravel```文档来自由的编写，也可以在控制器中直接重写映射函数来快速的完成它：
```php
protected function abilitiesMap()
{
    return "用户列表";
}
```
```php
protected function abilitiesMap()
{
    return [
        'index' => '用户列表'
    ];
}
```
```abilitiesMap```可以返回一个字符串或一个数组。字符串时，当前控制器下的所有方法，都需要这个权限才能访问。数组非常好理解，方法名称对应匹配的权限。