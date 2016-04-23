<?php
namespace Midnite81\Prowl;

use Illuminate\Support\ServiceProvider;
use Midnite81\Prowl\Services\ProwlNotifier;
use Prowl\Connector;
use Prowl\Message;


class ProwlServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    /**
     * Bootstrap the application events.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../config/prowl.php' => config_path('prowl.php')
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../config/prowl.php', 'prowl');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('midnite81.prowl', function ($app) {
            return new ProwlNotifier($app->make(Connector::class));
        });

        $this->app->alias('midnite81.prowl', 'Midnite81\Prowl\Contracts\Services\ProwlNotifier');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['midnite81.prowl', 'Midnite81\Prowl\Contracts\Services\Messaging'];
    }
}