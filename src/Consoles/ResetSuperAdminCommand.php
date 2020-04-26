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
use Spatie\Permission\Contracts\Role;
use Tanwencn\Admin\Database\Eloquent\User;

class ResetSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:resetSuperAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset SuperAdmin';

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
        app(Role::class)::findOrCreate('superadmin', 'admin');
        $user = User::findOrNew(1);
        $user->id = 1;
        $user->email = 'admin@admin.com';
        $user->name = 'administrator';
        $user->password = 'admin';
        if($user->save())
            $user->assignRole('superadmin');

        $this->info('Reset SuperAdmin is complete');
    }
}
