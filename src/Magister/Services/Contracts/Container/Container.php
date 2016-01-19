<?php

namespace Magister\Services\Contracts\Container;

/**
 * Interface Container
 * @package Magister
 */
interface Container
{
    /**
     * Register a binding with the container.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function bind($abstract, $concrete);

    /**
     * Register a service as a singleton.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function singleton($abstract, $concrete);

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function make($abstract);

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param string $abstract
     * @return bool
     */
    public function bound($abstract);
}
