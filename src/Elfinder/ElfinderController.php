<?php namespace Tanwencn\Admin\Elfinder;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class ElfinderController extends Controller
{
    protected $package = 'elfinder';

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        //$this->middleware('can:elfinder');
        $this->app = $app;
    }

    public function showIndex()
    {
        return view('admin::elfinder.standalonepopup')->with($this->getViewVars());
    }

    public function showPopup($input_id)
    {
        return view('admin::elfinder.standalonepopup')->with($this->getViewVars())
            ->with(compact('input_id'));
    }

    public function showFilePicker($input_id)
    {
        $type = Request::input('type');
        $mimeTypes = implode(',', array_map(function ($t) {
            return "'" . $t . "'";
        }, explode(',', $type)));
        return $this->app['view']
            ->make($this->package . '::filepicker')
            ->with($this->getViewVars())
            ->with(compact('input_id', 'type', 'mimeTypes'));
    }

    public function showConnector()
    {
        $key = config('admin.file.disk');
        $disk = app('filesystem')->disk($key);
//var_dump($disk->getDriver());exit;
        if ($disk instanceof FilesystemAdapter) {
            $root = array_merge(config('admin.file.options', []), [
                'driver' => 'Flysystem',
                'filesystem' => $disk->getDriver(),
                'alias' => $key,
            ]);
        }

        if (app()->bound('session.store')) {
            $sessionStore = app('session.store');
            $session = new LaravelSession($sessionStore);
        } else {
            $session = null;
        }

        $opts = ['roots' => [$root], 'session' => $session];

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    protected function getViewVars()
    {
        $onlyMimes = config('admin.file.onlyMimes');
        $arr = explode('-', $this->app->config->get('app.locale'));
        if(isset($arr[1])) $arr[1] = strtoupper($arr[1]);
        $locale = implode('_', $arr);

        return compact('locale', 'onlyMimes');
    }
}
