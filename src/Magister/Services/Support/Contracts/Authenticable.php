<?php
namespace Magister\Services\Support\Contracts;

/**
 * Interface Authenticable
 * @package Magister
 */
interface Authenticable
{
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier();
}