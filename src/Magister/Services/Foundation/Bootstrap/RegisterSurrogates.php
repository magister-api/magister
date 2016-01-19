<?php

namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Services\Foundation\AliasLoader;
use Magister\Services\Support\Surrogates\Surrogate;

/**
 * Class RegisterSurrogates
 * @package Magister
 */
class RegisterSurrogates
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        Surrogate::clearResolvedInstances();

        Surrogate::setSurrogateApplication($app);

        AliasLoader::getInstance($app->config['app.aliases'])->register();
    }
}
