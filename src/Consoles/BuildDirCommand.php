<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Admin\Consoles;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
use Tanwencn\Admin\Database\Eloquent\User;

class BuildDirCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:dir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Directory';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->files = app('files');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = app_path('Admin');

        $path = $directory . "/Controllers";
        if (!is_dir($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        } else {
            $this->line("<error>{$path} directory already exists !</error> ");
        }

        $path = $directory . "/routes.php";
        if (!$this->files->exists($path)) {
            $this->files->put($path, "<?php");
        } else {
            $this->line("<error>{$path} directory already exists !</error> ");
        }

        $this->line("<info>Directory build completed !</info> ");
    }

}
