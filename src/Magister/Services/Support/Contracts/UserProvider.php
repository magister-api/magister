<?php
namespace Magister\Services\Support\Contracts;

/**
 * Interface UserProvider
 * @package Magister
 */
interface UserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByCredentials(array $credentials);
}