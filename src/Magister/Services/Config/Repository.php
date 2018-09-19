<?php

namespace Magister\Services\Config;

class Repository
{
    protected $files;

    /**
     *	Constructor.
     *
     * @param array $config Loaded settings
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     *	Get a specific configuration value.
     *
     * @param string $key
     *
     * @return string
     */
    public function get(string $key)
    {
        $key = explode('.', $key);

        return $this->files[$key[0]][$key[1]];
    }
}
