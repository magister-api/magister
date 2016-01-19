<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Surrogate
 * @package Magister
 */
abstract class Surrogate
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
     * Resolve the surrogate instance from the container.
     *
     * @param string $name
     * @return mixed
     */
    protected static function resolveSurrogateInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    /**
     * Get the object behind the surrogate.
     *
     * @return mixed
     */
    public static function getSurrogateRoot()
    {
        return static::resolveSurrogateInstance(static::getSurrogateAccessor());
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
    protected static function getSurrogateAccessor()
    {
        throw new \RuntimeException("The Surrogate class does not implement a getSurrogateAccessor method.");
    }

    /**
     * Set the application instance.
     *
     * @param \Magister\Magister $app
     * @return void
     */
    public static function setSurrogateApplication($app)
    {
        static::$app = $app;
    }

    /**
     * Get the application instance.
     *
     * @return \Magister\Magister
     */
    public static function getSurrogateApplication()
    {
        return static::$app;
    }

    /**
     * Dynamically handle incoming requests.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getSurrogateRoot();

        switch (count($args)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($args[0]);
            case 2:
                return $instance->$method($args[0], $args[1]);
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
