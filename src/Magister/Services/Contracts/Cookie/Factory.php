<?php

namespace Magister\Services\Contracts\Cookie;

/**
 * Interface Factory
 * @package Magister
 */
interface Factory
{
    /**
     * Create a new cookie.
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function make($name, $value, $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true);

    /**
     * Create a cookie that lasts forever.
     *
     * @param string $name
     * @param string $value
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function forever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true);

    /**
     * Expire the given cookie.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     * @return bool
     */
    public function forget($name, $path = null, $domain = null);
}
