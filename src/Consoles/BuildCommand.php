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
use Symfony\Component\Finder\Finder;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:build';

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
        $base_path = app_path('Admin').DIRECTORY_SEPARATOR;

        $directories = iterator_to_array(
            Finder::create()->directories()->ignoreDotFiles(true)->in(__DIR__.'/../../build')
        );

        foreach ($directories as $director){
            $path = $base_path.$director->getRelativePathname();
            if (!file_exists($path)) {
                $this->files->makeDirectory($path, 0777, true, true);
            } else {
                $this->error("{$path} directory already exists !");
            }
        }

        $files = iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(true)->in(__DIR__.'/../../build')
        );
        foreach ($files as $file){
            $path = $base_path.$file->getRelativePathname();
            if (!file_exists($path)) {
                $this->files->copy($file->getPathname(), $path);
            } else {
                $this->error("{$path} file already exists !");
            }
        }

        $this->info("Directory build completed !");
    }
}
