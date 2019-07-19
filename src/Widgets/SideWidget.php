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
use Tanwencn\Admin\Database\Eloquent\Concerns\HasChildren;

class SideWidget extends AbstractWidget
{

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($name, $class)
    {
        if(in_array(HasChildren::class, class_uses_recursive($class, 'tree'))){
            $data = $class::tree()->get();
        }else{
            $data = $class::all();
        }

        $class_name = class_basename($class);

        return view('admin::widgets.menu_setting', compact('data', 'name', 'class_name', 'class'));
    }
}
