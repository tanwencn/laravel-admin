---
id: captcha
title: 验证码登录
---

## 1、安装验证码插件
```composer require mews/captcha```

```php artisan vendor:publish --provider Mews\Captcha\CaptchaServiceProvider```

## 2.修改登录视图和控制器
### 2.1 变更视图文件
复制```/resources/views/_auth``````app\Admin\Views```目录到```app\Admin\Views```下。

打开```_auth\login.blade.php``文件，在密码下方添加代码：
```html
<div class="form-group has-feedback {!! !$errors->has('captcha') ?: 'has-error' !!}">
     @if($errors->has('captcha')) 
         @foreach ($errors->get('captcha') as $message)
         <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label>
         </br>
         @endforeach
     @endif
     <div style="overflow: hidden; display: flex;
    justify-content: space-between;">
        <div style="max-width: 100%; margin-right: 5px">
            <input type="text" class="form-control" placeholder="验证码" name="captcha">
        </div>
        <div style="width: 120px">{!! captcha_img() !!}</div>
    </div>
</div>
```
### 2.1 变更登录验证逻辑
在```app\Admin\Controller```下创建```LoginController.php```
```php
namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Tanwencn\Admin\Http\Controllers\LoginController as Controller;

class LoginController extends Controller
{
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'captcha' => 'required|captcha',
        ], [
            'captcha' => '验证码错误'
        ], [
            'captcha' => '验证码',
            'captcha.captcha' => '123'
        ]);

        parent::validateLogin($request);
    }
}
```
并改变配置文件```admin.auth.login.controller```指向到新建的登录控制器。

### END