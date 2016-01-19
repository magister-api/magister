<?php

namespace Magister\Services\Contracts\Events;

/**
 * Interface Dispatcher
 * @package Magister
 */
interface Dispatcher
{
    /**
     * Register an event listener with the dispatcher.
     *
     * @param string $event
     * @param mixed $listener
     * @param int $priority
     * @return void
     */
    public function listen($event, $listener, $priority = 0);

    /**
     * Determine if a given event has listeners.
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListeners($eventName);

    /**
     * Fire an event and call the listeners.
     *
     * @param string $event
     * @param mixed $payload
     * @param bool $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false);

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param string $event
     * @return void
     */
    public function forget($event);
}
