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

        if ($page == 1) session()->forget('admin_logs_view.read');
        $this->isRefresh() ? session('admin_logs_view.last') : session()->forget('admin_logs_view.last');

        $data = $current ? $this->reverseData($current_file) : [];

        $eof = session('admin_logs_view.last') != 0;

        return $this->view('index', compact('tree', 'statistics', 'current', 'data', 'page', 'eof'));
    }

    protected function reverseData(SplFileInfo $file)
    {
        $items = $this->page(20, $file);

        return array_filter($items);
    }

    protected function totalLines(\SplFileObject $file)
    {
        $start = microtime(TRUE);

        $lines = 0;
        $file->setFlags(\SplFileObject::READ_AHEAD);
        $lines += iterator_count($file) - 1; // -1 gives the same number as "wc -l"

        echo '<br>';
        $end = microtime(TRUE);
        echo 'sg耗时' . $deltime = $end - $start;
        return $lines;
    }

    protected function page(int $maxLine, SplFileInfo $file)
    {
        $line = session('admin_logs_view.read', $this->totalLines($file->openFile()));

        $time = time();

        $items = [];
        for ($i = 0; $i <= $maxLine; $i++) {
            $items[$i] = $this->parseLine($line, $file->openFile());
            if (empty($items[$i]) || (time() - $time) > 10) break;
        }

        //断点记录
        if (!$this->isRefresh()) {
            session([
                'admin_logs_view' => [
                    'last' => $line,
                    'read' => $line,
                    'time' => request()->query('timestrap')
                ]
            ]);
        }

        return $items;
    }

    protected function parseLine(&$line, \SplFileObject $file)
    {
        $content = [];
        $result = [];
        $while = true;
        while ($line > 0 && $while) {
            $file->seek($line);
            $content[$line] = trim($file->current());
            if (preg_match('/\[\d{4}-\d{2}-\d{2}.*\].*/', $content[$line])) $while = false;
            $line--;

        }
        if ($content) {
            $data = array_reverse(array_filter($content));
            preg_match('/^\[(\d{4}-\d{2}-\d{2}.+\d)\] (.+?)\.(.+?): (.*)/', $data[0], $result);
            //$result[] = implode('', $data);
        }
        return $result;
    }

    private function isRefresh()
    {
        return request()->filled('page') && request()->filled('timestrap') && request()->query('timestrap') == session('admin_logs_view.time');
    }

    protected function abilitiesMap()
    {
        return "laravel_logs";
    }
}