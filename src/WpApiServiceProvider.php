<?php

namespace Ammonkc\WpApi;

use Ammonkc\WpApi\Auth\WpJwtAuth;
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

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'ammonkc/wp-api');
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->singleton(WpApiClient::class, function ($app) {
            $base_url = $app['config']->get('wp-api.base_url');
            $auth     = $app['config']->get('wp-api.auth');
            $options  = $app['config']->get('wp-api.guzzle_options');
            $serialize = $app['config']->get('wp-api.serialize_cookie');

            $client = new WpApiClient(new GuzzleAdapter(new Client($options)), $base_url);

            if (in_array($auth['driver'], ['token', 'jwt', 'jwt_token'])) {
                $client->setCredentials(new WpJwtAuth(\Cookie::get('wpapi_jwt_token'), $serialize));
            } else {
                $client->setCredentials(new WpBasicAuth($auth['user'], $auth['password']));
            }

            return $client;
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
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
