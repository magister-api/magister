<?php

namespace Magister\Services\Cookie;

use Closure;
use Exception;
use Magister\Services\Encryption\Encrypter;
use Magister\Services\Contracts\Cookie\Factory as JarContract;

/**
 * Class CookieJar
 * @package Magister
 */
class CookieJar implements JarContract
{
    /**
     * The encrypter instance.
     *
     * @var \Magister\Services\Encryption\Encrypter
     */
    protected $encrypter;

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
     * Create a new cookiejar instance.
     *
     * @param \Magister\Services\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Determine if a cookie exists and is not null.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Get the value of the given cookie.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;

        if (! is_null($value)) {
            return $this->decrypt($value);
        }

        return $default instanceof Closure ? $default() : $default;
    }

    /**
     * Decrypt the given cookie value.
     *
     * @param string $value
     * @return mixed|null
     */
    protected function decrypt($value)
    {
        try {
            return $this->encrypter->decrypt($value);
        } catch (Exception $e) {
            return null;
        }
    }

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

        $value = $this->encrypter->encrypt($value);

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
    public function setDefaultPathAndDomain($path, $domain)
    {
        list($this->path, $this->domain) = [$path, $domain];

        return $this;
    }

    /**
     * Get the encrypter instance.
     *
     * @return \Magister\Services\Encryption\Encrypter
     */
    public function getEncrypter()
    {
        return $this->encrypter;
    }
}
