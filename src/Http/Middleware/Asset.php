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
            ->before('/vendor/laravel-admin/admin/app.min.css')
            ->before('/vendor/laravel-admin/admin/app.min.js')
            ->add('/vendor/laravel-admin/jquery-scrollTable/jquery-scrollTable.min.js')
            ->jsdelivr('npm/admin-lte@2.4.17/dist/css/AdminLTE.min.css', 999)
            ->jsdelivr('npm/admin-lte@2.4.17/dist/js/adminlte.min.js', 999)
            /** 插件 */
            ->jsdelivr('npm/jquery@3/dist/jquery.min.js', null, 'head')
            ->jsdelivr('npm/fastclick@1.0/lib/fastclick.min.js')

            ->addBag('vendor/laravel-admin/jquery-ui/jquery-ui.min')
            ->jsdelivr('gh/Studio-42/elFinder@2.1/js/elfinder.min.js')

            ->jsdelivrBag('npm/bootstrap@3/dist/{type}/bootstrap.min')
            ->jsdelivr('npm/admin-lte@2.4.17/dist/css/skins/_all-skins.min.css')
            ->jsdelivr('gh/FortAwesome/Font-Awesome@4/css/font-awesome.min.css')
            ->jsdelivrBag('npm/select2@4.0.5/dist/{type}/select2.min')
            ->jsdelivr('npm/select2@4/dist/js/i18n/'.config('app.locale').'.min.js')
            ->jsdelivrBag('npm/toastr@2/build/toastr.min')
            ->jsdelivrBag('npm/nprogress@0.2.0/nprogress.min')
            ->jsdelivrBag('npm/jquery-confirm@3/dist/jquery-confirm.min')
            ->jsdelivr('npm/bootstrap-switch@3/dist/css/bootstrap3/bootstrap-switch.min.css')
            ->jsdelivr('npm/bootstrap-switch@3/dist/js/bootstrap-switch.min.js')
            ->jsdelivr('npm/icheck@1.0/icheck.min.js')
            ->jsdelivr('npm/icheck@1.0/skins/all.css');

        return $next($request);
    }
}
