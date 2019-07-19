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
use Tanwencn\Admin\AdminServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(!config('app.key'))
            $this->call('key:generate');

        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $this->call('vendor:publish', [
            '--provider' => AdminServiceProvider::class
        ]);
        /*$this->call('vendor:publish', [
            '--provider' => PermissionServiceProvider::class,
            '--tag' => 'migrations'
        ]);*/
        $this->call('migrate');

        /*$this->info('Attempting to set User model as parent to App\User');
        if (file_exists(app_path('User.php'))) {
            $str = file_get_contents(app_path('User.php'));

            if ($str !== false) {
                $str = str_replace('extends Authenticatable', "extends \Tanwencn\Admin\Database\Eloquent\User", $str);

                file_put_contents(app_path('User.php'), $str);
            }
        } else {
            $this->warn('Unable to locate "app/User.php".  Did you move this file?');
            $this->warn('You will need to update this manually.  Change "extends Authenticatable" to "extends \Tanwencn\Admin\Database\Eloquent\User" in your User model');
        }*/

        $this->call('admin:registerPermissions');
    }
}
