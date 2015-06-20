<?php
namespace Magister\Services\Http;

use Magister\Services\Support\ServiceProvider;
use GuzzleHttp\Client;

/**
 * Class HttpServiceProvider
 * @package Magister
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGuzzle();
    }

    /**
     * Register the Guzzle driver.
     *
     * @return void
     */
    protected function registerGuzzle()
    {
        $this->app->singleton('http', function($app)
        {
            return new Client([
                'base_uri' => $app['url'],
                'cookies' => true,
                'http_errors' => false
            ]);
        });
    }
}