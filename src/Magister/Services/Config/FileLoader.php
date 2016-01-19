<?php

namespace Magister\Services\Config;

use Magister\Services\Filesystem\Filesystem;

/**
 * Class FileLoader
 * @package Magister
 */
class FileLoader implements LoaderInterface
{
    /**
     * The filesystem instance.
     *
     * @var \Magister\Services\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The default configuration path.
     *
     * @var string
     */
    protected $defaultPath;

    /**
     * All of the named path hints.
     *
     * @var array
     */
    protected $hints = [];

    /**
     * A cache of whether namespaces and groups exists.
     *
     * @var array
     */
    protected $exists = [];

    /**
     * Create a new file configuration loader.
     *
     * @param \Magister\Services\Filesystem\Filesystem $files
     * @param string $defaultPath
     */
    public function __construct(Filesystem $files, $defaultPath)
    {
        $this->files = $files;
        $this->defaultPath = $defaultPath;
    }

    /**
     * Load the given configuration group.
     *
     * @param string $group
     * @param string $namespace
     * @return array
     */
    public function load($group, $namespace = null)
    {
        $items = [];

        $path = $this->getPath($namespace);

        if (is_null($path)) {
            return $items;
        }

        $file = "{$path}/{$group}.php";

        if ($this->files->exists($file)) {
            $items = $this->files->getRequire($file);
        }

        return $items;
    }

    /**
     * Determine if the given group exists.
     *
     * @param string $group
     * @param string $namespace
     * @return bool
     */
    public function exists($group, $namespace = null)
    {
        $key = $group . $namespace;

        if (isset($this->exists[$key])) {
            return $this->exists[$key];
        }

        $path = $this->getPath($namespace);

        if (is_null($path)) {
            return $this->exists[$key] = false;
        }

        $file = "{$path}/{$group}.php";

        $exists = $this->files->exists($file);

        return $this->exists[$key] = $exists;
    }

    /**
     * Get the configuration path for a namespace.
     *
     * @param string $namespace
     * @return string
     */
    protected function getPath($namespace)
    {
        if (is_null($namespace)) {
            return $this->defaultPath;
        } elseif (isset($this->hints[$namespace])) {
            return $this->hints[$namespace];
        }
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }

    /**
     * Returns all registered namespaces with the config loader.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->hints;
    }

    /**
     * Get a file's contents by requiring it.
     *
     * @param string $path
     * @return mixed
     */
    protected function getRequire($path)
    {
        return $this->files->getRequire($path);
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Magister\Services\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }
}
