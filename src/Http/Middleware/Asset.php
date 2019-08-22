<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Middleware;

use Closure;
use Tanwencn\Admin\Facades\Admin;

class Asset
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Admin::asset()
            ->add('/vendor/laravel-admin/admin/app.min.css', 999, 'head')
            ->add('/vendor/laravel-admin/admin/app.min.js', 999, 'head')
            ->add('/vendor/laravel-admin/jquery-scrollTable/jquery-scrollTable.min.js')
            ->add('https://cdn.jsdelivr.net/npm/admin-lte@2.4.17/dist/css/AdminLTE.min.css', 990)
            ->add('https://cdn.jsdelivr.net/npm/admin-lte@2.4.17/dist/js/adminlte.min.js', 990)
            /** 插件 */
            ->add('https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js', 1, 'head')
            ->add('https://cdn.jsdelivr.net/npm/fastclick@1.0/lib/fastclick.min.js')

            ->addBag('vendor/laravel-admin/jquery-ui/jquery-ui.min')
            ->add('https://cdn.jsdelivr.net/gh/Studio-42/elFinder@2.1/js/elfinder.min.js')

            ->addBag('https://cdn.jsdelivr.net/npm/bootstrap@3/dist/{type}/bootstrap.min')
            ->add('https://cdn.jsdelivr.net/npm/admin-lte@2.4.17/dist/css/skins/_all-skins.min.css')
            ->add('https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@4/css/font-awesome.min.css')
            ->addBag('https://cdn.jsdelivr.net/npm/select2@4.0.5/dist/{type}/select2.min')
            ->add('https://cdn.jsdelivr.net/npm/select2@4/dist/js/i18n/'.config('app.locale').'.min.js')
            ->addBag('https://cdn.jsdelivr.net/npm/toastr@2/build/toastr.min')
            ->addBag('https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min')
            ->addBag('https://cdn.jsdelivr.net/npm/jquery-confirm@3/dist/jquery-confirm.min')
            ->add('https://cdn.jsdelivr.net/npm/bootstrap-switch@3/dist/css/bootstrap3/bootstrap-switch.min.css')
            ->add('https://cdn.jsdelivr.net/npm/bootstrap-switch@3/dist/js/bootstrap-switch.min.js')
            ->add('https://cdn.jsdelivr.net/npm/icheck@1.0/icheck.min.js')
            ->add('https://cdn.jsdelivr.net/npm/icheck@1.0/skins/all.css')
            ->add('https://cdn.jsdelivr.net/npm/icheck@1.0/icheck.min.js')

        ;

        return $next($request);
    }
}
