<?php

namespace Magister\Services\Translation;

use Magister\Services\Support\ServiceProvider;
use Magister\Services\Translation\Translator;

/**
 * Class TranslatorServiceProvider.
 */
class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     * 
     * @return void
     */
    public function register()
    {
        $this->app->bind('translator', function ($app) {
            return new Translator($app['config']['dictionary']);
        });
    }
}
