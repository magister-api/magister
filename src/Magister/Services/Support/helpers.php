<?php

use Magister\Services\Support\Dumper\Dumper;
use Magister\Services\Support\Facades\Config;

if ( ! function_exists('dd'))
{
   /**
    * Dump the passed variables and end the script.
    *
    * @param mixed
    * @return void
    */
    function dd()
    {
        array_map(function($x) { (new Dumper)->dump($x); }, func_get_args());
        die;
    }
}

if ( ! function_exists('with'))
{
    /**
     * Return the given object. Useful for chaining.
     *
     * @param mixed $object
     * @return mixed
     */
    function with($object)
    {
        return $object;
    }
}

if ( ! function_exists('array_set'))
{
    /**
     * Set an array item to a given value using dot notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if ( ! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = [];
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if ( ! function_exists('array_get'))
{
    /**
     * Get an item from an array using dot notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_array($array) || ! array_key_exists($segment, $array))
            {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }
}

if ( ! function_exists('array_first'))
{
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array $array
     * @param callable $callback
     * @param mixed $default
     * @return mixed
     */
    function array_first($array, callable $callback, $default = null)
    {
        foreach ($array as $key => $value)
        {
            if (call_user_func($callback, $key, $value)) return $value;
        }
        return $default;
    }
}

if ( ! function_exists('array_last'))
{
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array $array
     * @param callable $callback
     * @param mixed $default
     * @return mixed
     */
    function array_last($array, callable $callback, $default = null)
    {
        return array_first(array_reverse($array), $callback, $default);
    }
}

if ( ! function_exists('array_flatten'))
{
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @return array
     */
    function array_flatten($array)
    {
        $return = [];

        array_walk_recursive($array, function($x) use (&$return)
        {
            $return[] = $x;
        });

        return $return;
    }
}

if ( ! function_exists('array_pluck'))
{
    /**
     * Pluck an array of values from an array.
     *
     * @param array $array
     * @param string $value
     * @param string $key
     * @return array
     */
    function array_pluck($array, $value, $key = null)
    {
        $results = [];

        foreach ($array as $item)
        {
            $itemValue = data_get($item, $value);

            if (is_null($key))
            {
                $results[] = $itemValue;
            }
            else
            {
                $itemKey = data_get($item, $key);

                $results[$itemKey] = $itemValue;
            }
        }
        return $results;
    }
}

if ( ! function_exists('data_get'))
{
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed $target
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) return $target;

        foreach (explode('.', $key) as $segment)
        {
            if (is_array($target))
            {
                if ( ! array_key_exists($segment, $target))
                {
                    return $default;
                }
                $target = $target[$segment];
            }
            elseif ($target instanceof ArrayAccess)
            {
                if ( ! isset($target[$segment]))
                {
                    return $default;
                }
                $target = $target[$segment];
            }
            elseif (is_object($target))
            {
                if ( ! isset($target->{$segment}))
                {
                    return $default;
                }
                $target = $target->{$segment};
            }
            else
            {
                return $default;
            }
        }
        return $target;
    }
}

if ( ! function_exists('starts_with'))
{
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|array $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) return true;
        }

        return false;
    }
}