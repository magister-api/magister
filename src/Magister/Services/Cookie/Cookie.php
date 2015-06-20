<?php
namespace Magister\Services\Cookie;

/**
 * Class Cookie
 * @package Magister
 */
class Cookie
{
    /**
     * The default path.
     *
     * @var string
     */
    protected $path = '/';

    /**
     * The default domain.
     *
     * @var string
     */
    protected $domain = null;

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
    public function make($name, $value, $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        list($path, $domain) = $this->getPathAndDomain($path, $domain);

        $expire = ($expire == 0) ? 0 : time() + ($expire * 60);

        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

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
    public function forever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        return $this->make($name, $value, 525948, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Expire the given cookie.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     * @return bool
     */
    public function forget($name, $path = null, $domain = null)
    {
        return $this->make($name, null, -525948, $path, $domain);
    }

    /**
     * Get the default path and domain.
     *
     * @param string $path
     * @param string $domain
     * @return array
     */
    public function getPathAndDomain($path, $domain)
    {
        return [$path ?: $this->path, $domain ?: $this->domain];
    }

    /**
     * Set the default path and domain.
     *
     * @param string $path
     * @param string $domain
     * @return $this
     */
    public function setPathAndDomain($path, $domain)
    {
        list($this->path, $this->domain) = [$path, $domain];

        return $this;
    }
}