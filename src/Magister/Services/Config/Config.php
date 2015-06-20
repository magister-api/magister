<?php
namespace Magister\Services\Config;

/**
 * Class Config
 * @package Magister
 */
class Config implements \ArrayAccess
{
    /**
     * The Loader implementation.
     *
     * @var \Magister\Services\Config\Loader
     */
    protected $loader;

    /**
     * The array of loaded groups.
     *
     * @var array
     */
    protected $loaded = [];

    /**
     * Create a new Config instance.
     *
     * @param \Magister\Services\Config\Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Set a given configuration value.
     *
     * @param array|string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key))
        {
            foreach ($key as $innerKey => $innerValue)
            {
                array_set($this->loaded, $innerKey, $innerValue);
            }
        }
        else
        {
            array_set($this->loaded, $key, $value);
        }
    }

    /**
     * Get the config value for a given key.
     *
     * @param string $key
     * @param array $replace
     * @param null $default
     * @return string
     */
    public function get($key, array $replace = [], $default = null)
    {
        list($group, $item) = $this->parseKey($key);

        $this->load($group);

        $line = $this->getLine(
            $group, $item, $replace, $default
        );

        if ( ! isset($line)) return $key;

        return $line;
    }

    /**
     * Determine if a config value exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) !== $key;
    }

    /**
     * Replace all occurrences of a string in a group.
     *
     * @param string $group
     * @param array $replace
     * @return void
     */
    public function replace($group, array $replace)
    {
        $group = ucfirst($group);

        $this->load($group);

        $loaded = [];

        foreach ($this->loaded[$group] as $key => $line)
        {
            $loaded[$key] = $this->makeReplacements($line, $replace);
        }

        $this->loaded[$group] = $loaded;
    }

    /**
     * Parse a key into a group
     *
     * @param string $key
     * @return array
     */
    public function parseKey($key)
    {
        $segments = explode('.', $key);

        $group = ucfirst($segments[0]);

        if (count($segments) == 1)
        {
            $segments = [$group, null];
        }
        else
        {
            $item = implode('.', array_slice($segments, 1));

            $segments = [$group, $item];
        }

        return $segments;
    }

    /**
     * Load the specified config group.
     *
     * @param string $group
     * @return void
     */
    public function load($group)
    {
        if ($this->isLoaded($group)) return;

        $lines = $this->loader->load($group);

        $this->loaded[$group] = $lines;
    }

    /**
     * Get the config loader implementation.
     *
     * @return \Magister\Services\Config\Loader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Determine if the given group has been loaded.
     *
     * @param string $group
     * @return bool
     */
    protected function isLoaded($group)
    {
        return isset($this->loaded[$group]);
    }

    /**
     * Retrieve a language key out of the loaded array.
     *
     * @param string $group
     * @param string $item
     * @param array $replace
     * @param null $default
     * @return string
     */
    protected function getLine($group, $item, array $replace = [], $default = null)
    {
        $line = array_get($this->loaded[$group], $item, $default);

        if (is_string($line) || is_bool($line))
        {
            return $this->makeReplacements($line, $replace);
        }
        elseif (is_array($line) && count($line) > 0)
        {
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
    protected function makeReplacements($line, array $replace = [])
    {
        foreach ($replace as $key => $value)
        {
            $line = str_replace(':' . $key, $value, $line);
        }

        return $line;
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