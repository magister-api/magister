<?php

namespace Magister\Services\Http;

use GuzzleHttp\Cookie\SetCookie;
use Magister\Services\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJar as GuzzleCookieJar;

/**
 * Class SessionCookieJar
 * @package Magister
 */
class SessionCookieJar extends GuzzleCookieJar
{
    /**
     * The Magister cookie creator service.
     *
     * @var \Magister\Services\Cookie\CookieJar
     */
    protected $cookie;

    /**
     * Create a new session cookie jar instance.
     *
     * @param \Magister\Services\Cookie\CookieJar $cookie
     * @param bool $strict
     * @param array $cookies
     */
    public function __construct(CookieJar $cookie, $strict = false, $cookies = [])
    {
        $this->cookie = $cookie;

        $cookies = $this->load($cookies);

        parent::__construct($strict, $cookies);
    }

    /**
     * Save cookies to the client's session.
     *
     * @param \GuzzleHttp\Cookie\SetCookie $cookie
     * @return bool
     */
    public function setCookie(SetCookie $cookie)
    {
        $successful = parent::setCookie($cookie);

        if ($successful) {
            $this->cookie->make($cookie->getName(), $cookie);

            return true;
        }

        return false;
    }

    /**
     * Load the contents of the client's session into the data array.
     *
     * @param array $cookies
     * @return array
     */
    protected function load($cookies)
    {
        foreach ($_COOKIE as $name => $value) {
            $cookie = $this->cookie->get($name);

            if ($cookie instanceof SetCookie) {
                $cookies[] = $cookie;
            }
        }

        return $cookies;
    }
}
