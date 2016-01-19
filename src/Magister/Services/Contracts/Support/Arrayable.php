<?php

namespace Magister\Services\Contracts\Support;

/**
 * Interface Arrayable
 * @package Magister
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
