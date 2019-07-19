<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Widgets;

use App\User;
use Arrilot\Widgets\AbstractWidget;
use Tanwencn\Admin\Database\Eloquent\Comment;
use Tanwencn\Admin\Database\Eloquent\Page;
use Tanwencn\Admin\Database\Eloquent\Post;

class DashboardWidget extends AbstractWidget
{
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $posts_count = Post::count();
        $pages_count = Page::count();

        $users_count = User::count();
        $comments_count = Comment::count();

        return view('admin::widgets.dashboard', compact('posts_count', 'pages_count', 'users_count', 'comments_count'));
    }
}
