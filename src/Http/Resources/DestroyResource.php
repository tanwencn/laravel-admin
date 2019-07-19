<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/7 下午6:48
 */

namespace Tanwencn\Admin\Http\Resources;

trait DestroyResource
{
    public function destroy($id)
    {
        $ids = explode(',', $id);

        $this->model::destroy($ids);

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}