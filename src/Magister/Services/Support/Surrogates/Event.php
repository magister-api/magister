<?php

namespace Magister\Services\Support\Surrogates;

/**
 * Class Event.
 */
class Event extends Surrogate
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getSurrogateAccessor()
    {
        return 'events';
    }
}
