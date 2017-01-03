<?php

namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;

/**
 * Class RegisterProviders.
 */
class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        $app->registerProviders();
    }
}
