<?php
namespace Magister\Services\Support\Contracts;

/**
 * Interface Jsonable
 * @package Magister
 */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0);
}