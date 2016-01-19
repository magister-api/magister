<?php

namespace Magister\Services\Events;

use Magister\Services\Container\Container;
use Magister\Services\Contracts\Events\Dispatcher as DispatcherContract;

class Dispatcher implements DispatcherContract
{
    /**
     * The IoC container instance.
     *
     * @var \Magister\Services\Container\Container
     */
    protected $container;

    /**
     * The registered event listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * The sorted event listeners.
     *
     * @var array
     */
    protected $sorted = [];

    /**
     * Create a new event dispatcher instance.
     *
     * @param \Magister\Services\Container\Container $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: new Container;
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param string $event
     * @param mixed $listener
     * @param int $priority
     * @return void
     */
    public function listen($event, $listener, $priority = 0)
    {
        $this->listeners[$event][$priority][] = $this->makeListener($listener);

        unset($this->sorted[$event]);
    }

    /**
     * Determine if a given event has listeners.
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListeners($eventName)
    {
        return isset($this->listeners[$eventName]);
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param string $event
     * @param mixed $payload
     * @param bool $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false)
    {
        $responses = [];

        if (! is_array($payload)) {
            $payload = [$payload];
        }

        $payload[] = $event;

        foreach ($this->getListeners($event) as $listener) {
            $response = call_user_func_array($listener, $payload);

            if (! is_null($response) && $halt) {
                return $response;
            }

            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }

        return $halt ? null : $responses;
    }

    /**
     * Get all of the listeners for a given event name.
     *
     * @param string $eventName
     * @return array
     */
    public function getListeners($eventName)
    {
        if (! isset($this->sorted[$eventName])) {
            $this->sortListeners($eventName);
        }

        return $this->sorted[$eventName];
    }

    /**
     * Sort the listeners for a given event by priority.
     *
     * @param string $eventName
     * @return array
     */
    protected function sortListeners($eventName)
    {
        $this->sorted[$eventName] = [];

        if (isset($this->listeners[$eventName])) {
            krsort($this->listeners[$eventName]);

            $this->sorted[$eventName] = call_user_func_array('array_merge', $this->listeners[$eventName]);
        }
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param mixed $listener
     * @return mixed
     */
    public function makeListener($listener)
    {
        if (is_string($listener)) {
            $listener = $this->createClassListener($listener);
        }

        return $listener;
    }

    /**
     * Create a class based listener using the IoC container.
     *
     * @param mixed $listener
     * @return \Closure
     */
    public function createClassListener($listener)
    {
        $container = $this->container;

        return function () use ($listener, $container) {
            $segments = explode('@', $listener);

            $method = count($segments) == 2 ? $segments[1] : 'handle';

            $callable = [new $segments[0], $method];

            $data = func_get_args();

            return call_user_func_array($callable, $data);
        };
    }

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param string $event
     * @return void
     */
    public function forget($event)
    {
        unset($this->listeners[$event]);
        
        unset($this->sorted[$event]);
    }
}
