<?php

namespace Ammonkc\WpApi;

use Ammon\WpApi\Auth\WpJwtAuth;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;

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
        $this->app->singleton(WpApiClient::class, function ($app) {
            $base_url = $this->app['config']->get('wp-api.base_url');
            $auth     = $this->app['config']->get('wp-api.auth');
            $options  = $this->app['config']->get('wp-api.guzzle_options');

            if ($auth['driver'] == 'token') {
                $authDriver = new WpJwtAuth(\Cookie::get('pandaonline-token'));
            } else {
                $authDriver = new WpBasicAuth($auth['user'], $auth['password']);
            }

            $client = new WpApiClient(new GuzzleAdapter(new Client($options)), $base_url);
            $client->setCredentials($authDriver);

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
