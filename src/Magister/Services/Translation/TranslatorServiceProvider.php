<?php

namespace Magister\Services\Translation;

use Magister\Services\Support\ServiceProvider;
use Magister\Services\Translation\Translator;

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
            $dictionary = $app['config']['dictionary'];

            return new Translator($dictionary);
        });
    }
}
