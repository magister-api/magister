<?php

namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;

/**
 * Class BootProviders
 * @package Magister
 */
class BootProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        $app->boot();
    }
}
