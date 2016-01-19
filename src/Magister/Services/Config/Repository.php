<?php

namespace Magister\Services\Config;

use Magister\Services\Support\NamespacedItemResolver;
use Magister\Services\Contracts\Config\Repository as ConfigContract;

/**
 * Class Repository
 * @package Magister
 */
class Repository extends NamespacedItemResolver implements \ArrayAccess, ConfigContract
{
    /**
     * The loader implementation.
     *
     * @var \Magister\Services\Config\LoaderInterface
     */
    protected $loader;

    /**
     * All of the configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new configuration repository.
     *
     * @param \Magister\Services\Config\LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) !== $key;
    }

    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        list($namespace, $group, $item) = $this->parseKey($key);

        $collection = $this->getCollection($group, $namespace);

        $this->load($group, $namespace, $collection);

        if (is_null($item)) {
            $this->items[$collection] = $value;
        } else {
            array_set($this->items[$collection], $item, $value);
        }
    }

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param array $replace
     * @param mixed $default
     * @return mixed
     */
    public function get($key, array $replace = [], $default = null)
    {
        list($namespace, $group, $item) = $this->parseKey($key);

        $collection = $this->getCollection($group, $namespace);

        $this->load($group, $namespace, $collection);

        $line = $this->getLine(
            $collection, $item, $replace, $default
        );

        if (is_null($line)) {
            return $key;
        }

        return $line;
    }

    /**
     * Replace all occurrences of a string in a group.
     *
     * @param string $key
     * @param string $search
     * @param mixed $replace
     * @return void
     */
    public function replace($key, $search, $replace)
    {
        list($namespace, $group) = $this->parseKey($key);

        $collection = $this->getCollection($group, $namespace);

        $this->load($group, $namespace, $collection);

        $items = [];

        foreach ($this->items[$collection] as $key => $value) {
            $items[$key] = str_replace(':' . $search, $replace, $value);
        }

        $this->items[$collection] = $items;
    }

    /**
     * Load the configuration group for the key.
     *
     * @param string $group
     * @param string $namespace
     * @param string $collection
     * @return void
     */
    protected function load($group, $namespace, $collection)
    {
        if (isset($this->items[$collection])) {
            return;
        }

        $items = $this->loader->load($group, $namespace);

        $this->items[$collection] = $items;
    }

    /**
     * Retrieve a language line out the loaded array.
     *
     * @param string $collection
     * @param string $item
     * @param array $replace
     * @param mixed $default
     * @return string|null
     */
    protected function getLine($collection, $item, array $replace, $default = null)
    {
        $line = array_get($this->items[$collection], $item, $default);

        if (is_string($line) || is_bool($line)) {
            return $this->makeReplacements($line, $replace);
        } elseif (is_array($line) && count($line) > 0) {
            return $line;
        }
    }

    /**
     * Make the place-holder replacements on a line.
     *
     * @param string $line
     * @param array $replace
     * @return string
     */
    protected function makeReplacements($line, array $replace)
    {
        foreach ($replace as $key => $value) {
            $line = str_replace(':'.$key, $value, $line);
        }

        return $line;
    }

    /**
     * Get the collection identifier.
     *
     * @param string $group
     * @param string $namespace
     * @return string
     */
    protected function getCollection($group, $namespace = null)
    {
        $namespace = $namespace ?: '*';

        return $namespace . '::' . $group;
    }

    /**
     * Get the loader implementation.
     *
     * @return \Magister\Services\Config\LoaderInterface
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Set the loader implementation.
     *
     * @param \Magister\Services\Config\LoaderInterface $loader
     * @return void
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Set a configuration option.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get a configuration option.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Unset a configuration option.
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }
}
