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
        $this->publishes([
            realpath(__DIR__ . '/../resources/views') => base_path('resources/views/'),
        ], 'view');


        $this->publishes([
            realpath(__DIR__ . '/../resources/public') => public_path() ,
        ], 'assets');


        $router->any('maker', 'MakerController@index');
        
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
