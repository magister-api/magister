<?php

namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Services\Config\Repository;

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
        $loader = $app->getConfigLoader();

        $app->bind('config', new Repository($loader));

        mb_internal_encoding('UTF-8');
    }
}
