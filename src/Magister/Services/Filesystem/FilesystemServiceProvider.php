<?php

namespace Magister\Services\Filesystem;

use Magister\Services\Support\ServiceProvider;

/**
 * Class FilesystemServiceProvider
 * @package Magister
 */
class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerNativeFilesystem();
    }

    /**
     * Register the native filesystem implementation.
     *
     * @return void
     */
    protected function registerNativeFilesystem()
    {
        $this->app->singleton('files', function () {
            return new Filesystem;
        });
    }
}
