<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2020/5/9 8:45 下午
 */

namespace Tanwencn\Admin\Http\Middleware;


use Tanwencn\Admin\Facades\Admin;

abstract class AbstractMenuMiddleware
{
    protected $app;

    public function __construct()
    {
        $this->app = Admin::menu();
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $this->boot();
        return $next($request);
    }

    public function boot(){}

    public function __call($name, $arguments)
    {
        return call_user_func([$this->app, $name], ...$arguments);
    }
}