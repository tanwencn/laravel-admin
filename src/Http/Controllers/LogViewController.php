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
use Illuminate\Support\Arr;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\URL;

class LogViewController extends Controller
{
    use Package;

    protected $rows_line = 0;
    protected $eof = false;

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $current = $request->filled('f') ? decrypt($request->query('f'), false) : "";
        if ($page == 1) $this->rows_key = 0;
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
            $fileName = $log_path . '/' . $pathname;

            /*if(is_file($fileName) && !$current)
                $current = $fileName;*/

            if ($current == $fileName)
                $current_file = $file;

            Arr::set($tree, $key, $fileName);
        }

        $statistics = "";

        if ($page == 1) session(['log_view_line' => 0]);
        $this->rows_line = $this->isRefresh()?session('log_view_line_last'):session('log_view_line', 0);

        $data = $current ? $this->data($current_file) : [];

        $eof = $this->eof;

        return $this->view('index', compact('tree', 'statistics', 'current', 'data', 'page', 'eof'));
    }

    protected function data(SplFileInfo $file)
    {
        $file = (new \SplFileObject($file));
        $file->seek($this->rows_line);
        $once_rows = 0;
        $content = "";

        while ($this->eof = $file->valid()) {
            $line = $file->fgets() . "\n";
            if ($once_rows > config('admin.laravel_logs.read_once_rows', 10000) && preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/', $line)) {
                if (!$this->isRefresh()) {
                    session(['log_view_line_last' => $this->rows_line]);
                    session(['log_view_line' => $file->key()-1]);
                    session(['log_view_line_time' => request()->query('timestrap')]);
                }
                break;
            };

            $content .= $line;
            $once_rows++;
        }

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

    private function isRefresh()
    {
        return request()->filled('page') && request()->filled('timestrap') && request()->query('timestrap') == session('log_view_line_time');
    }

    protected function abilitiesMap()
    {
        return "laravel_logs";
    }
}