---
id: rewrite_view
title: 视图重写
---

后台的默认布局和一些基础的模板文件是集成在扩展目录的，一般情况下你不需要对这些进行改动，如果需要的话，可以按照下面的步骤进行操作。

## 登录页模板修改示例

### 1.复制视图文件到指定位置
复制```/vendor/tanwencn/laravel-admin/resources/views```下的所有文件和目录到```/resources/views/vendor/admin```目录。

###  2.更改登录页的模板文件
打开登录页模板文件：```/resources/views/vendor/admin/auth/login.blade.php```，在此对其进行修改完成后保存即可。
