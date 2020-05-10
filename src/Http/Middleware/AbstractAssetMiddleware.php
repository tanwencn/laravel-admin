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

abstract class AbstractAssetMiddleware
{
    protected $app;

    public function __construct()
    {
        $this->app = Admin::asset();
    }

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
        $this->header($this->app->header());
        $this->footer($this->app->footer());

        return $next($request);
    }

    public function header($app){}
    public function footer($app){}

    public function __call($name, $arguments = [])
    {
        return call_user_func([$this->app, $name], ...$arguments);
    }
}
