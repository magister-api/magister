<?php

namespace Magister\Services\Encryption;

use Magister\Services\Support\ServiceProvider;

/**
 * Class EncryptionServiceProvider
 * @package Magister
 */
class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('encrypter', function ($app) {
            return new Encrypter($app['config']['app.key']);
        });
    }
}
