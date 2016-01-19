<?php

namespace Magister\Services\Foundation;

/**
 * Class AliasLoader
 * @package Magister
 */
class AliasLoader
{
    /**
     * The array of class aliases.
     *
     * @var array
     */
    protected $aliases;

    /**
     * Indicates if a loader has been registered before.
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * The singleton instance of the alias loader.
     *
     * @var \Magister\Services\Foundation\AliasLoader
     */
    protected static $instance;

    /**
     * Create a new alias loader instance.
     *
     * @param array $aliases
     */
    private function __construct($aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Create a singleton alias loader instance.
     *
     * @param array $aliases
     * @return static
     */
    public static function getInstance(array $aliases = [])
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($aliases);
        }

        $aliases = array_merge(static::$instance->getAliases(), $aliases);

        static::$instance->setAliases($aliases);

        return static::$instance;
    }

    /**
     * Register the loader on the auto-loader stack.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->isRegistered()) {
            $this->registerAutoloader();
            
            $this->registered = true;
        }
    }

    /**
     * Register the class alias auto-loader.
     *
     * @return void
     */
    protected function registerAutoloader()
    {
        spl_autoload_register([$this, 'load'], true, true);
    }

    /**
     * Load a class alias if it is registered.
     *
     * @param string $alias
     * @return void
     */
    public function load($alias)
    {
        if (isset($this->aliases[$alias])) {
            class_alias($this->aliases[$alias], $alias);
        }
    }

    /**
     * Set the registered aliases.
     *
     * @param array $aliases
     * @return void
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Get the registered aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Indicates if the loader has been registered.
     *
     * @return bool
     */
    public function isRegistered()
    {
        return $this->registered;
    }

    /**
     * Set the "registered" state of the loader.
     *
     * @param bool $value
     * @return void
     */
    public function setRegistered($value)
    {
        $this->registered = $value;
    }

    /**
     * Set the value of the singleton alias loader.
     *
     * @param \Magister\Services\Foundation\AliasLoader $loader
     * @return void
     */
    public static function setInstance($loader)
    {
        static::$instance = $loader;
    }

    /**
     * The clone method.
     *
     * @return void
     */
    private function __clone()
    {
    }
}
