<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2019/7/28 8:05 PM
 */

namespace Tanwencn\Admin\Elfinder;


class ElFinder extends \elFinder
{
    public function exec($cmd, $args)
    {
        $args['mimes'] = false;
        foreach ($this->volumes as $volume) {
            $key = $volume->getOption('admin_key');
            if($key) {
                $mimes = config("admin.elfinder.{$key}.onlyMimes", []);
                $volume->setMimesFilter($mimes);
            }
        }
        return parent::exec($cmd, $args);
    }


}