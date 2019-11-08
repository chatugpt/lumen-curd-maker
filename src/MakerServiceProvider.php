<?php
namespace Le2le\Maker;


use Illuminate\Support\ServiceProvider;
class MakerServiceProvider extends ServiceProvider
{


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     *
     * @return void
     */
    public function boot()
    {

        $viewPath = realpath(__DIR__ . '/../resources/views');
        $this->loadViewsFrom($viewPath, 'maker');

        $views = resource_path('views');
        $files = ['header', 'index', 'layout', 'pagination'];

        foreach ($files as $file)
        {
            if(!file_exists($views . DIRECTORY_SEPARATOR . $file . '.blade.php'))
            {
                copy($viewPath .DIRECTORY_SEPARATOR . $file . '.blade.php', $views . DIRECTORY_SEPARATOR . $file . '.blade.php');
            }
        }

        $viewPath = realpath(__DIR__ . '/../resources/views');
        $this->loadViewsFrom($viewPath, 'maker');


        $config['namespace'] = __NAMESPACE__;
        //定义路由
        app()->router->group($config, function ($router) {
            $router->get('maker', 'MakerController@index');
            $router->post('maker', 'MakerController@index');
        });


    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {


    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

    }

}
