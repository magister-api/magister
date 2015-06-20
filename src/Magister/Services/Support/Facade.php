<?php
namespace Magister\Services\Support;

/**
 * Class Facade
 * @package Magister
 */
abstract class Facade
{
    /**
     * The application instance.
     *
     * @var \Magister\Magister
     */
    protected static $app;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * Resolve the facade instance from the container.
     *
     * @param string $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) return $name;

        if (isset(static::$resolvedInstance[$name]))
        {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    /**
     * Get the object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Clear all of the instances.
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * Return the name of the service.
     *
     * @return void
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException("The Facade class does not implement a getFacadeAccessor method.");
    }

    /**
     * Set the application instance.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public static function setFacadeApplication($app)
    {
        static::$app = $app;
    }

    /**
     * Get the application instance.
     *
     * @return \Magister\Magister
     */
    public static function getFacadeApplication()
    {
        return static::$app;
    }

    /**
     * Dynamically handle incoming requests.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();

        switch (count($arguments))
        {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($arguments[0]);
            case 2:
                return $instance->$method($arguments[0], $arguments[1]);
            case 3:
                return $instance->$method($arguments[0], $arguments[1], $arguments[2]);
            case 4:
                return $instance->$method($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            default:
                return call_user_func_array([$instance, $method], $arguments);
        }
    }
}