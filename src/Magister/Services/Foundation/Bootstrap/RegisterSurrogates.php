<?php
namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Services\Support\Surrogates\Surrogate;
use Magister\Services\Foundation\AliasLoader;

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