<?php

namespace Ammonkc\WpApi;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
use Vnn\WpApiClient\WpClient;

class WpApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('wp-api.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->singleton(WpApi::class, function ($app) {
            $base_url = $this->app['config']->get('wp-api.base_url');
            $auth     = $this->app['config']->get('wp-api.auth');

            $client = new WpClient(new GuzzleAdapter(new Client()), $base_url);
            $client->setCredentials(new WpBasicAuth($auth['user'], $auth['password']));

            return $client;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['wp-api'];
    }
}
