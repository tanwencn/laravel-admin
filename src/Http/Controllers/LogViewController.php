<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2019/8/24 2:33 PM
 */

namespace Tanwencn\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Finder\Finder;

class LogViewController extends Controller
{
    public function index(Request $request)
    {
        $current = $request->filled('f')?decrypt($request->query('f'), false):"";
        $current_file = null;
        $tree = [];
        $log_path = storage_path('logs');
        $files = iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(true)->in($log_path)->sort(function ($a, $b) {
                return -($a->getMTime() - $b->getMTime());
            }),
            false
        );

        foreach ($files as $file) {
            $pathname = $file->getRelativePathname();
            $info = pathinfo($pathname);
            $key = $info['dirname'] == '.' ? $info['filename'] : str_replace('/', '.', $info['dirname']) . '.' . $info['filename'];
            $fileName = $log_path.'/'.$pathname;

            if(is_file($fileName) && !$current)
                $current = $fileName;

            if($current == $fileName)
                $current_file = $file;

            array_set($tree, $key, $fileName);
        }

        $statistics = "";

        $data = $this->data($current_file);

        return $this->view('index', compact('tree', 'statistics', 'current', 'data'));
    }

    protected function data(SplFileInfo $file){
        /*if (app('files')->size($file) >= 15*1024*1024) {
            return null;
        }*/

        $content = $file->getContents();

        preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/', $content, $headings);
        $log_data = preg_split('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/', $content, null);
        if ($log_data[0] < 1)
            array_shift($log_data);

        $headings = array_shift($headings);

        $results = [];
        foreach ($headings as $key => $heading){
            preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.+?)\.(.+?): (.*)/', $heading, $results[$key]);
            array_push($results[$key], $log_data[$key]);
        }

        return array_reverse($results);
    }
}