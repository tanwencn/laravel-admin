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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\URL;

class LogViewController extends Controller
{
    use Package;

    protected $eof = false;

    public function index(Request $request)
    {
        $current = $request->filled('f') ? decrypt($request->query('f'), false) : "";
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

            Arr::set($tree, $key, $fileName);
        }

        $statistics = "";

        session()->forget('admin_logs_view');

        return $this->view('index', compact('tree', 'statistics', 'current', 'data', 'page', 'eof'));
    }

    public function api(Request $request)
    {
        $lines = $request->query('lines', 20);
        if(session('admin_logs_view.last', 0) >= $lines) return [];
        $file = $request->filled('f') ? decrypt($request->query('f'), false) : "";
        return $this->reverseData(new \SplFileInfo($file));
    }

    protected function reverseData(\SplFileInfo $file)
    {
        $items = $this->page($file);

        return array_values(array_filter($items));
    }

    protected function totalLines(\SplFileObject $file)
    {
        $lines = 0;
        $file->setFlags(\SplFileObject::READ_AHEAD);
        $lines += iterator_count($file) - 1; // -1 gives the same number as "wc -l"
        return $lines;
    }

    protected function page(\SplFileInfo $file)
    {
        $line = session('admin_logs_view.read', $this->totalLines($file->openFile()));

        $time = time();

        $items = [];
        $read_line = 0;
        while (true){
            $read_line++;
            $items[$read_line] = $this->parseLine($line, $file->openFile());
            if (empty($items[$read_line]) || (time() - $time) > 3) break;
        }

        //断点记录
        session([
            'admin_logs_view' => [
                'last' => session('admin_logs_view.last', 0)+$read_line,
                'read' => $line,
                'time' => request()->query('timestrap')
            ]
        ]);

        return $items;
    }

    protected function parseLine(&$line, \SplFileObject $file)
    {
        $content = [];
        $result = [];
        $while = true;
        while ($line >= 0 && $while) {
            $file->seek($line);
            $content[$line] = trim($file->current());
            if (preg_match('/\[\d{4}-\d{2}-\d{2}.*\].*/', $content[$line])) $while = false;
            $line--;

        }
        if ($content) {
            $data = array_reverse(array_filter($content));
            preg_match('/^\[(\d{4}-\d{2}-\d{2}.+\d)\] (.+?)\.(.+?): (.*)/', $data[0], $result);
            //dd($data);
            unset($result[0]);
            $result[] = implode("\n", $data);
        }
        return array_values($result);
    }

    protected function abilitiesMap()
    {
        return "laravel_logs";
    }
}