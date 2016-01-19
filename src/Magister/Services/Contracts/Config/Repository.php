<?php

namespace Magister\Services\Contracts\Config;

/**
 * Interface Repository
 * @package Magister
 */
interface Repository
{
    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Set a given configuration value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param array $replace
     * @param mixed $default
     * @return mixed
     */
    public function get($key, array $replace = [], $default = null);

    /**
     * Replace all occurrences of a string in a group.
     *
     * @param string $key
     * @param string $search
     * @param mixed $replace
     * @return void
     */
    public function replace($key, $search, $replace);

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all();
}
