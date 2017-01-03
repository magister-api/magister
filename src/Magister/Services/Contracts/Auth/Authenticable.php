<?php

namespace Magister\Services\Contracts\Auth;

/**
 * Interface Authenticable.
 */
interface Authenticable
{
    /**
     * Get the unique identifier for the user.
     * @return mixed
     */
    public function getAuthIdentifier();
}
