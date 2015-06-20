<?php
namespace Magister\Services\Auth;

use Magister\Services\Support\Contracts\UserProvider;
use Magister\Services\Support\Contracts\Guard as GuardContract;
use Magister\Services\Support\Contracts\Authenticable as UserContract;

/**
 * Class Guard
 * @package Magister
 */
class Guard implements GuardContract
{
    /**
     * The currently authenticated user.
     *
     * @var \Magister\Services\Support\Contracts\Authenticable
     */
    protected $user;

    /**
     * The user provider implementation.
     *
     * @var \Magister\Services\Support\Contracts\UserProvider
     */
    protected $provider;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Create a new Auth instance.
     *
     * @param \Magister\Services\Support\Contracts\UserProvider $provider
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Magister\Services\Support\Contracts\Authenticable|null
     */
    public function user()
    {
        if ($this->loggedOut) return;

        if ( ! is_null($this->user))
        {
            return $this->user;
        }
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if ($this->loggedOut) return;

        if ($this->check())
        {
            return $this->user()->getAuthIdentifier();
        }
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param array $credentials
     * @param bool $login
     * @return bool
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user))
        {
            if ($login) $this->login($user);

            return true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @return bool
     */
    protected function hasValidCredentials($user)
    {
        return ! is_null($user);
    }

    /**
     * Log a user into the application.
     *
     * @param \Magister\Services\Support\Contracts\Authenticable $user
     * @return void
     */
    public function login(UserContract $user)
    {
        $this->setUser($user);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->clearUserDataFromStorage();

        $this->user = null;
        $this->loggedOut = true;
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->provider->clearCookies();
    }

    /**
     * Set the current user.
     *
     * @param \Magister\Services\Support\Contracts\Authenticable $user
     * @return void
     */
    public function setUser(UserContract $user)
    {
        $this->user = $user;

        $this->loggedOut = false;
    }

    /**
     * Return the currently cached user.
     *
     * @return mixed|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param \Magister\Services\Support\Contracts\UserProvider $provider
     * @return void
     */
    public function setProvider(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return \Magister\Services\Support\Contracts\UserProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }
}