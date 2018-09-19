<?php

namespace Magister\Services\Foundation;

use Exception;
use Magister\Services\Config\Repository;
use Magister\Magister;

/**
 * Class LoadConfiguration.
 */
class LoadConfiguration
{
    protected $configurationFiles = [
        'url',
        'config',
    ];

    protected $files = [];

    /**
     * Bootstrap LoadConfiguration
     * .
     *
     * @param SkyLines $api
     *
     * @return void
     */
    public function bootstrap(Magister $api)
    {
        $files = $this->getConfigurationFiles($this->configurationFiles, $api->basePath());

        $api->bind('config', new Repository($files));
    }

    /**
     *	Get given configuration files.
     *
     * @param array $files
     *
     * @return array
     */
    protected function getConfigurationFiles(array $files, string $basePath)
    {
        foreach ($files as $file) {
            $this->files[$file] = $this->requireFile($file, $basePath);
        }

        return $this->files;
    }

    /**
     * Require the given file.
     *
     * @param string $file
     * @param string $basePath
     *
     * @return array
     */
    protected function requireFile(string $file, string $basePath)
    {
        return require $this->findPath($file, $basePath);
    }

    /**
     *	Construct the given path.
     *
     * @param string $file
     * @param string $basePath
     *
     * @return string
     */
    protected function findPath($file, $basePath)
    {
        if (!file_exists($path = "$basePath/Config/$file.php")) {
            throw new Exception('Could not find file: '.$path, 1);
        }

        return $path;
    }
}
