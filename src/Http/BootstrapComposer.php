<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/7/9 下午3:11
 */

namespace Tanwencn\Admin\Http;

use Illuminate\View\Factory;

class BootstrapComposer
{
    protected $viewFactory;

    public function __construct(Factory $factory)
    {
        $this->viewFactory = $factory;
    }

    public function compose()
    {
        $hints = $this->viewFactory->getFinder()->getHints();

        $names = $hints['admin'];
        krsort($names);

        foreach ($names as $name) {
            $this->viewFactory->prependNamespace('pagination', $name . '/pagination');
        }
    }
}