<?php

namespace Magister\Services\Auth;

use Magister\Services\Contracts\Auth\Authenticable as UserContract;
use Magister\Services\Contracts\Auth\Guard as GuardContract;
use Magister\Services\Contracts\Auth\UserProvider;
use Magister\Services\Contracts\Events\Dispatcher;
use Magister\Services\Cookie\CookieJar;

/**
 * Class Guard.
 */
class Guard implements GuardContract
{
    /**
     * The currently authenticated user.
     *
     * @var \Magister\Services\Contracts\Auth\Authenticable
     */
    protected $user;

    /**
     * The user provider implementation.
     *
     * @var \Magister\Services\Contracts\Auth\UserProvider
     */
    protected $provider;

    /**
     * The Magister cookie creator service.
     *
     * @var \Magister\Services\Cookie\CookieJar
     */
    protected $cookie;

    /**
     * The event dispatcher instance.
     *
     * @var \Magister\Services\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Create a new guard instance.
     *
     * @param \Magister\Services\Contracts\Auth\UserProvider $provider
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
        return !is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Magister\Services\Contracts\Auth\Authenticable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        // If we have already retrieved the user for the current request we can just
        // return it back immediately. We do not want to pull the user data every
        // request into the method because that would tremendously slow an app.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $id = $this->cookie->get($this->getName());

        $user = null;

        if (!is_null($id)) {
            $user = $this->provider->retrieveByToken();
        }

        return $this->user = $user;
    }

    /**
     * Get the id for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        $id = $this->cookie->get($this->getName());

        if (is_null($id) && $this->user()) {
            $id = $this->user()->getAuthIdentifier();
        }

        return $id;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param array $credentials
     * @param bool  $login
     *
     * @return bool
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user)) {
            if ($login) {
                $this->login($user);
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     *
     * @return bool
     */
    protected function hasValidCredentials($user)
    {
        return !is_null($user);
    }

    /**
     * Log a user into the application.
     *
     * @param \Magister\Services\Contracts\Auth\Authenticable $user
     *
     * @return void
     */
    public function login(UserContract $user)
    {
        $this->updateSession($user->getAuthIdentifier());

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user);

        $this->setUser($user);
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param \Magister\Services\Contracts\Auth\Authenticable $user
     *
     * @return void
     */
    protected function fireLoginEvent($user)
    {
        if (isset($this->events)) {
            $this->events->fire('auth.login', $user);
        }
    }

    /**
     * Update the session with the given cookie.
     *
     * @param string $id
     *
     * @return void
     */
    protected function updateSession($id)
    {
        $this->cookie->make($this->getName(), $id);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->clearUserDataFromStorage();

        if (isset($this->events)) {
            $this->events->fire('auth.logout', $user);
        }

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
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
        $this->getCookieJar()->forget($this->getName());

        $recaller = $this->getRecallerName();

        $this->getCookieJar()->forget($recaller);

        $this->provider->removeToken();
    }

    /**
     * Get the name of the cookie used to store the "recaller".
     *
     * @return string
     */
    public function getRecallerName()
    {
        return 'SESSION_ID';
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_'.md5(get_class($this));
    }

    /**
     * Set the current user.
     *
     * @param \Magister\Services\Contracts\Auth\Authenticable $user
     *
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
     * Set the cookie creator instance used by the guard.
     *
     * @param \Magister\Services\Cookie\CookieJar $cookie
     *
     * @return void
     */
    public function setCookieJar(CookieJar $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Get the cookie creator instance used by the guard.
     *
     * @throws \RuntimeException
     *
     * @return \Magister\Services\Cookie\CookieJar
     */
    public function getCookieJar()
    {
        if (!isset($this->cookie)) {
            throw new \RuntimeException('Cookie jar has not been set.');
        }

        return $this->cookie;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param \Magister\Services\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Magister\Services\Contracts\Events\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param \Magister\Services\Contracts\Auth\UserProvider $provider
     *
     * @return void
     */
    public function setProvider(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return \Magister\Services\Contracts\Auth\UserProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
