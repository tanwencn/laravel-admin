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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
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

        if ($page == 1) session(['admin-log-view-line' => 0]);
        $this->rows_line = $this->isRefresh()?session('admin-log-view-line-last'):session('admin-log-view-line', 0);

        $data = $current ? $this->reverseData($current_file) : [];

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
            if ($once_rows > config('admin.laravel_logs.read_once_rows', 10000) && preg_match('/\[\d{4}-\d{2}-\d{2}.*\].*/', $line)) {
                if (!$this->isRefresh()) {
                    session(['admin-log-view-line-last' => $this->rows_line]);
                    session(['admin-log-view-line' => $file->key()-1]);
                    session(['admin-log-view-line-time' => request()->query('timestrap')]);
                }
                break;
            };

            $content .= $line;
            $once_rows++;
        }

        preg_match_all('/\[\d{4}-\d{2}-\d{2}.*\].*/', $content, $headings);
        $log_data = preg_split('/\[\d{4}-\d{2}-\d{2}.*\].*/', $content, null);
        if ($log_data[0] < 1)
            array_shift($log_data);

        $headings = array_shift($headings);

        $results = [];
        foreach ($headings as $key => $heading){
            preg_match('/^\[(\d{4}-\d{2}-\d{2}.+\d)\] (.+?)\.(.+?): (.*)/', trim($heading), $results[$key]);
            array_push($results[$key], $log_data[$key]);
        }

        return array_reverse($results);
    }

    protected function reverseData(SplFileInfo $file)
    {
        $items = $this->page(20, $file);

        return array_filter($items);
    }

    protected function totalLines(\SplFileObject $file){
        $start = microtime(TRUE);

        $lines = 0;
        $file->setFlags(\SplFileObject::READ_AHEAD);
        $lines += iterator_count($file) - 1; // -1 gives the same number as "wc -l"

        echo '<br>';
        $end = microtime(TRUE);
        echo 'sg耗时' . $deltime = $end - $start;
        return $lines;
    }

    protected function page(int $maxLine, SplFileInfo $file){
        $count = $this->totalLines($file->openFile());

        $time = time();
        $file = $file->openFile();
        $file->seek($count);

        $items = [];
        for ($i=0;$i<=20;$i++){
            $items[$i] = $this->parseLine($count, $file);
            if(empty($items[$i]) || (time() - $time) > 10) break;
        }

        return $items;
    }

    protected function parseLine(&$line, \SplFileObject $file){
        $content = [];
        $result = [];
        while ($line > 0) {
            $file->seek($line);
            $content[$line] = trim($file->current());
            if (preg_match('/\[\d{4}-\d{2}-\d{2}.*\].*/', $content[$line])){
                $line--;
                break;
            }
            $line--;
        }
        if($content) {
            $data = array_reverse(array_filter($content));
            preg_match('/^\[(\d{4}-\d{2}-\d{2}.+\d)\] (.+?)\.(.+?): (.*)/', $data[0], $result);
            //$result[] = implode('', $data);
        }
        return $result;
    }

    private function isRefresh()
    {
        return request()->filled('page') && request()->filled('timestrap') && request()->query('timestrap') == session('admin-log-view-line-time');
    }

    protected function abilitiesMap()
    {
        return "laravel_logs";
    }
}