<?php
namespace Magister\Foundation;

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
     * The singleton instance of the AliasLoader.
     *
     * @var \Magister\Foundation\AliasLoader
     */
    protected static $instance;

    /**
     * Create a new AliasLoader instance.
     *
     * @param array $aliases
     */
    private function __construct($aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Create a singleton AliasLoader instance.
     *
     * @param array $aliases
     * @return static
     */
    public static function getInstance(array $aliases = [])
    {
        if (is_null(static::$instance))
        {
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
        if ( ! $this->isRegistered())
        {
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
        if (isset($this->aliases[$alias]))
        {
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
     * The clone method.
     *
     * @return void
     */
    private function __clone() {}
}