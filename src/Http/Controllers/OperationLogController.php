<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Controllers;


use Tanwencn\Admin\Database\Eloquent\OperationLog;

class OperationLogController extends Controller
{
    public function index(){
        $this->authorize('operationlog');

        $model = new OperationLog();

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }


}
