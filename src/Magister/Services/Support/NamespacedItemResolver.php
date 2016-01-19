<?php

namespace Magister\Services\Support;

/**
 * Class NamespacedItemResolver
 * @package Magister
 */
class NamespacedItemResolver
{
    /**
     * A cache of the parsed items.
     *
     * @var array
     */
    protected $parsed = [];

    /**
     * Parse a key into namespace, group, and item.
     *
     * @param string $key
     * @return array
     */
    public function parseKey($key)
    {
        if (isset($this->parsed[$key])) {
            return $this->parsed[$key];
        }

        if (strpos($key, '::') === false) {
            $segments = explode('.', $key);

            $parsed = $this->parseBasicSegments($segments);
        } else {
            $parsed = $this->parseNamespacedSegments($key);
        }

        return $this->parsed[$key] = $parsed;
    }

    /**
     * Parse an array of basic segments.
     *
     * @param array $segments
     * @return array
     */
    protected function parseBasicSegments(array $segments)
    {
        $group = $segments[0];

        if (count($segments) == 1) {
            return [null, $group, null];
        } else {
            $item = implode('.', array_slice($segments, 1));

            return [null, $group, $item];
        }
    }

    /**
     * Parse an array of namespaced segments.
     *
     * @param string $key
     * @return array
     */
    protected function parseNamespacedSegments($key)
    {
        list($namespace, $item) = explode('::', $key);

        $itemSegments = explode('.', $item);

        $groupAndItem = array_slice($this->parseBasicSegments($itemSegments), 1);

        return array_merge([$namespace], $groupAndItem);
    }

    /**
     * Set the parsed value of a key.
     *
     * @param string $key
     * @param array $parsed
     * @return void
     */
    public function setParsedKey($key, $parsed)
    {
        $this->parsed[$key] = $parsed;
    }
}
