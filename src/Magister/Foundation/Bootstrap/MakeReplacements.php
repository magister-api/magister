<?php
namespace Magister\Foundation\Bootstrap;

use Magister\Magister;

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
        $app->config->replace('locations', [
            'id' => '5462',
            'enrollment' => '384063'
        ]);
    }
}