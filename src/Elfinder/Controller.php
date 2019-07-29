<?php namespace Tanwencn\Admin\Elfinder;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $package = 'elfinder';

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $session;

    public function __construct(Application $app)
    {
        //$this->middleware('can:elfinder');
        $this->app = $app;

        $this->session = new LaravelSession(app('session.store'));
    }

    public function showIndex(Request $request)
    {
        $this->setDisks($request->input('disks', []));

        return view('admin::elfinder.standalonepopup')->with($this->getViewVars());
    }

    public function showConnector()
    {
        $roots = [];
        $disks = $this->getDisks();
        foreach ((array)$disks as $key) {
            $config = config("admin.elfinder.{$key}");
            $disk = app('filesystem')->disk($config['disk']);
            if ($disk instanceof FilesystemAdapter) {
                $roots[$key] = array_merge($config, [
                    'driver' => 'Flysystem',
                    'filesystem' => $disk->getDriver(),
                    'alias' => isset($config['alias']) ? $config['alias'] : $key,
                    'admin_key' => $key
                ]);
            }
        }

        $opts = [
            'roots' => array_values($roots),
            'session' => $this->session,
            /*'bind' => [
                'open' => [$this, 'mySimpleCallback']
            ]*/
        ];

        // run elFinder
        $connector = new Connector(new ElFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    protected function setDisks($disks)
    {
        $this->session->set('admin_elfinder_disks', $disks);
    }

    protected function getDisks()
    {
        $filters = [];
        $disks = $this->session->get('admin_elfinder_disks');
        $config = config("admin.elfinder");
        foreach ((array)$disks as $key) {
            if ($key && isset($config[$key]))
                $filters[] = $key;
        }
        if (empty($filters)) $filters[] = 'default';

        return array_unique($filters);
    }

    protected function getViewVars()
    {
        $arr = explode('-', $this->app->config->get('app.locale'));
        if (isset($arr[1])) $arr[1] = strtoupper($arr[1]);
        $locale = implode('_', $arr);

        $multiple = request('multiple', 0);
        $multiple = ($multiple == 'false' || !$multiple) ? 0 : 1;

        $is_tree = count($this->getDisks()) > 1;

        return compact('locale', 'is_tree', 'multiple');
    }
}
