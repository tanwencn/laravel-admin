<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Widgets;

use Arrilot\Widgets\AbstractWidget;

class AssetWidget extends AbstractWidget
{
    public function __construct($config)
    {
        $this->addConfigDefaults([
            'is_file' => true
        ]);
        parent::__construct($config);
    }


    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($type, $content)
    {
        $is_file = $this->config['is_file'];

        return view('admin::widgets.asset', compact('type', 'content', 'is_file'));
    }
}
