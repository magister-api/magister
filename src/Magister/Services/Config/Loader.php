<?php
namespace Magister\Services\Config;

use Magister\Exceptions\FileNotFoundException;

/**
 * Class Loader
 * @package Magister
 */
class Loader
{
    /**
     * Path to config folder.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new Loader instance.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Load the particular file in.
     *
     * @param string $file
     * @return array
     */
    public function load($file)
    {
        $items = [];

        $file = "{$this->path}/{$file}.php";

        if ($this->exists($file))
        {
            $items = $this->getRequire($file);
        }

        return $items;
    }

    /**
     * Check whether the file exists.
     *
     * @param string $file
     * @return bool
     */
    protected function exists($file)
    {
        return file_exists($file);
    }

    /**
     * If the file exists, require it.
     *
     * @param string $file
     * @return array
     * @throws \Magister\Exceptions\FileNotFoundException
     */
    protected function getRequire($file)
    {
        if ($this->isFile($file)) return require $file;

        throw new FileNotFoundException(sprintf('File does not exist at path "%s".', $file));
    }

    /**
     * Checks whether specified file is actually a file.
     *
     * @param string $file
     * @return bool
     */
    protected function isFile($file)
    {
        return is_file($file);
    }
}
