<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/7/9 下午3:11
 */

namespace Tanwencn\Admin\Http\ViewComposers;


class BootsrapComposer
{
    public function compose()
    {
        $view = view();
        $hints = $view->getFinder()->getHints();

        $view->prependNamespace('pagination', $hints['admin'][0] . '/pagination');
    }
}