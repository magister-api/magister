<?php

namespace Magister\Services\Contracts\Auth;

/**
 * Interface UserProvider
 * @package Magister
 */
interface UserProvider
{
    /**
     * Retrieve a user by their unique token.
     *
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByToken();

    /**
     * Remove the token for the given user in storage.
     *
     * @return void
     */
    public function removeToken();

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByCredentials(array $credentials);
}
