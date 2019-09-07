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
            ->before('/vendor/laravel-admin/app.min.css')
            ->before('/vendor/laravel-admin/app.min.js')
            ->before('/vendor/laravel-elfinder/app.min.js')
            ->jsdelivr('npm/admin-lte@2.4.17/dist/css/AdminLTE.min.css', 999)
            ->jsdelivr('npm/admin-lte@2.4.17/dist/js/adminlte.min.js', 999)
            /** 插件 */
            ->jsdelivr('npm/jquery@3.4/dist/jquery.min.js', null, 'head')
            ->jsdelivrCombile('npm/fastclick@1.0/lib/fastclick.min.js', 'gh/rochal/jQuery-slimScroll@1.3/jquery.slimscroll.min.js')
            ->jsdelivrCombile('gh/DataTables/DataTables@1.10.19/media/css/dataTables.bootstrap.min.css')
            ->jsdelivrCombile('gh/DataTables/DataTables@1.10.19/media/js/jquery.dataTables.min.js', 'gh/DataTables/DataTables@1.10.19/media/js/dataTables.bootstrap.min.js')

            ->addBag('vendor/laravel-elfinder/jquery-ui/jquery-ui.min')
            ->jsdelivr('gh/Studio-42/elFinder@2.1/js/elfinder.min.js')

            ->jsdelivrBag('npm/bootstrap@3/dist/{type}/bootstrap.min')
            ->jsdelivr('npm/admin-lte@2.4.17/dist/css/skins/_all-skins.min.css')
            ->jsdelivr('gh/FortAwesome/Font-Awesome@4/css/font-awesome.min.css')
            ->jsdelivr('gh/select2/select2@4.0/dist/css/select2.min.css')
            ->jsdelivr('gh/select2/select2@4.0/dist/js/select2.full.min.js')
            ->jsdelivr('gh/select2/select2@4.0/dist/js/i18n/'.config('app.locale').'.min.js')
            ->jsdelivrBag('npm/toastr@2.1/build/toastr.min')
            ->jsdelivrBag('npm/nprogress@0.2.0/nprogress.min')
            ->jsdelivrBag('npm/jquery-confirm@3.3/dist/jquery-confirm.min')
            ->jsdelivr('gh/Bttstrp/bootstrap-switch@3.3/dist/css/bootstrap3/bootstrap-switch.min.css')
            ->jsdelivr('gh/Bttstrp/bootstrap-switch@3.3/dist/js/bootstrap-switch.min.js')
            ->jsdelivr('npm/icheck@1.0/icheck.min.js')
            ->jsdelivr('npm/icheck@1.0/skins/all.css');

        return $next($request);
    }
}
