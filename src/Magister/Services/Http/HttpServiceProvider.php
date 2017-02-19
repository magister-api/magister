<?php

namespace Magister\Services\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Cache\CacheSubscriber;
use Magister\Services\Support\ServiceProvider;

/**
 * Class HttpServiceProvider.
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
        $this->app->singleton('http', function ($app) {
            $client = new Client([
                'base_url' => "https://{$app['school']}.magister.net/api/",
            ]);

            $client->setDefaultOption('exceptions', false);

            $client->setDefaultOption('cookies', new SessionCookieJar($app['cookie']));

            $client->setDefaultOption('headers', [
                'X-API-Client-ID' => env('MAGISTER_API_KEY', '12D8'),
            ]);

            CacheSubscriber::attach($client);

            return $client;
        });
    }
}
