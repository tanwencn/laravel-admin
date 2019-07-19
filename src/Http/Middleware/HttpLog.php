<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Tanwencn\Admin\Database\Eloquent\OperationLog;
use Tanwencn\Admin\Facades\Admin;

class HttpLog
{

    public function handle($request, Closure $next)
    {
        if (in_array(strtolower($request->method()), config('admin.logger.method'))) {

            $body = $request->except(array_merge(config('admin.logger.except', []), ['_token', '_method']));

            $files = $this->fileNames($request->files);

            OperationLog::create([
                'user_id' => Admin::user()->id,
                'uri' => substr($request->getPathInfo(), 0, 255),
                'method' => $request->method(),
                'ip' => $request->getClientIp(),
                'body' => json_encode(array_merge($body, $files), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ]);
        }

        return $next($request);
    }

    protected function fileNames($files)
    {
        if ($files instanceof FileBag) {
            $files = $files->all();
        } elseif (!is_array($files)) {
            $files = iterator_to_array($files);
        }

        return array_map(function ($file) {
            if (is_array($file)) {
                return $this->fileNames($file);
            }

            if ($file instanceof UploadedFile) {
                return [
                    'originalName' => $file->getClientOriginalName(),
                    'originalSize' => $file->getClientSize()
                ];
            } else {
                return "none";
            }

        }, (array)$files);
    }
}
