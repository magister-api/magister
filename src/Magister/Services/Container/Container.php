<?php

namespace Magister\Services\Container;

use Closure;
use ArrayAccess;
use InvalidArgumentException;
use Magister\Services\Contracts\Container\Container as ContainerContract;

/**
 * Class Container
 * @package Magister
 */
class Container implements ArrayAccess, ContainerContract
{
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Register a binding with the container.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Register a service as a singleton.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $this->share($concrete));
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function make($abstract)
    {
        if (! $this->bound($abstract)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $abstract));
        }

        $isFactory = is_object($this->bindings[$abstract]) && method_exists($this->bindings[$abstract], '__invoke');

        return $isFactory ? $this->bindings[$abstract]($this) : $this->bindings[$abstract];
    }

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param string $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return array_key_exists($abstract, $this->bindings);
    }

    /**
     * Remove the specified abstract from the container.
     *
     * @param string $abstract
     * @return void
     */
    public function remove($abstract)
    {
        unset($this->bindings[$abstract]);
    }

    /**
     * Returns a closure that stores the result of the given closure for
     * uniqueness in the scope of this instance of the container.
     *
     * @param \Closure $callable
     * @return \Closure
     */
    public static function share(Closure $callable)
    {
        return function ($c) use ($callable) {
            static $object;

            if (null === $object) {
                $object = $callable($c);
            }

            return $object;
        };
    }

    /**
     * Protects a callable from being interpreted as a service.
     *
     * @param \Closure $callable
     * @return \Closure
     */
    public static function protect(Closure $callable)
    {
        return function ($c) use ($callable) {
            return $callable;
        };
    }

    /**
     * Gets a parameter or the closure defining an object.
     *
     * @param string $abstract
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function raw($abstract)
    {
        if (! array_key_exists($abstract, $this->bindings)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $abstract));
        }

        return $this->bindings[$abstract];
    }

    /**
     * Extends an object definition.
     *
     * @param string $abstract
     * @param \Closure $callable
     * @return \Closure
     * @throws \InvalidArgumentException
     */
    public function extend($abstract, Closure $callable)
    {
        if (! array_key_exists($abstract, $this->bindings)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $abstract));
        }

        $factory = $this->bindings[$abstract];

        if (! ($factory instanceof Closure)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" does not contain an object definition.', $abstract));
        }

        return $this->bindings[$abstract] = function ($c) use ($callable, $factory) {
            return $callable($factory($c), $c);
        };
    }

    /**
     * Returns all defined value names.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->bindings);
    }

    /**
     * Register a binding with the container.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function __set($abstract, $concrete)
    {
        $this->bind($abstract, $concrete);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     */
    public function __get($abstract)
    {
        return $this->make($abstract);
    }

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param string $abstract
     * @return bool
     */
    public function __isset($abstract)
    {
        $this->bound($abstract);
    }

    /**
     * Remove the specified abstract from the container.
     *
     * @param string $abstract
     * @return void
     */
    public function __unset($abstract)
    {
        $this->remove($abstract);
    }

    /**
     * Register a binding with the container.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function offsetSet($abstract, $concrete)
    {
        $this->bind($abstract, $concrete);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     */
    public function offsetGet($abstract)
    {
        return $this->make($abstract);
    }

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param string $abstract
     * @return bool
     */
    public function offsetExists($abstract)
    {
        $this->bound($abstract);
    }

    /**
     * Remove the specified abstract from the container.
     *
     * @param string $abstract
     * @return void
     */
    public function offsetUnset($abstract)
    {
        $this->remove($abstract);
    }
}
