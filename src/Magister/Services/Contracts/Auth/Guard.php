<?php

namespace Magister\Services\Contracts\Auth;

/**
 * Interface Guard
 * @package Magister
 */
interface Guard
{
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check();

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest();

    /**
     * Get the currently authenticated user.
     *
     * @return \Magister\Services\Contracts\Auth\Authenticable|null
     */
    public function user();

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param array $credentials
     * @param bool $login
     * @return bool
     */
    public function attempt(array $credentials = [], $login = true);

    /**
     * Log a user into the application.
     *
     * @param \Magister\Services\Contracts\Auth\Authenticable $user
     * @return void
     */
    public function login(Authenticable $user);

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout();
}
