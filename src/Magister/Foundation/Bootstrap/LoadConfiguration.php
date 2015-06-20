<?php
namespace Magister\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Services\Config\Config;
use Magister\Services\Config\Loader;

/**
 * Class LoadConfiguration
 * @package Magister
 */
class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        $app->bind('config', new Config(
            new Loader($app->basePath() . DIRECTORY_SEPARATOR . 'Config')
        ));
    }
}