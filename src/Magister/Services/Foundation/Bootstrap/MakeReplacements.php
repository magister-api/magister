<?php

namespace Magister\Services\Foundation\Bootstrap;

use Auth;
use Magister\Magister;
use Magister\Models\Enrollment\Enrollment;

/**
 * Class MakeReplacements
 * @package Magister
 */
class MakeReplacements
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        if ($app->auth->check()) {
            $app->config->replace('url', 'id', Auth::id());
            $app->config->replace('url', 'enrollment', Enrollment::current()->Id);
        }
    }
}
