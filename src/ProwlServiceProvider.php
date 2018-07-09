<?php
namespace Midnite81\Prowl;

use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Support\ServiceProvider;
use Midnite81\Prowl\Contracts\Prowl as ProwlContract;
use Midnite81\Prowl\Services\ProwlNotifier;


class ProwlServiceProvider extends ServiceProvider
{

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
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('midnite81.prowl', ProwlContract::class);
        $this->app->bind('midnite81.prowl', function() {
            return new LaravelProwl(new Client(), new GuzzleMessageFactory(), config('prowl'));
        });
        $this->mergeConfigFrom(__DIR__ . '/../config/prowl.php', 'prowl');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'midnite81.prowl',
            ProwlContract::class,
        ];
    }
}