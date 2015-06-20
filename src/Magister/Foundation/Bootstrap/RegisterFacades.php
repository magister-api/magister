<?php
namespace Magister\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Services\Support\Facade;
use Magister\Foundation\AliasLoader;

/**
 * Class RegisterFacades
 * @package Magister
 */
class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);

        AliasLoader::getInstance($app->config['application.aliases'])->register();
    }
}