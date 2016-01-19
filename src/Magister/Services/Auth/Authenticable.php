<?php

namespace Magister\Services\Auth;

/**
 * Class Authenticable
 * @package Magister
 */
trait Authenticable
{
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}
