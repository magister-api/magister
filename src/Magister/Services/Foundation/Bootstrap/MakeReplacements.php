<?php

namespace Magister\Services\Foundation\Bootstrap;

use Magister\Magister;
use Magister\Models\Enrollment\Enrollment;

/**
 * Class MakeReplacements.
 */
class MakeReplacements
{
    /**
     * Bootstrap the given application.
     *
     * @param \Magister\Magister $app
     *
     * @return void
     */
    public function bootstrap(Magister $app)
    {
        if ($app->auth->check()) {
            // Temporary compatibility fix.
            if (!is_null($app->auth->user()) && is_null($app->auth->id())) {
                $app->config->replace('url', 'id', $app->auth->user()->Id);
            } else {
                $app->config->replace('url', 'id', $app->auth->id());
            }
            $app->config->replace('url', 'enrollment', Enrollment::current()->Id);
        }
    }
}
