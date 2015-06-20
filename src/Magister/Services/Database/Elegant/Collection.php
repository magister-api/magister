<?php
namespace Magister\Services\Database\Elegant;

use Magister\Services\Support\Contracts\Arrayable;
use Magister\Services\Support\Contracts\Jsonable;

/**
 * Class Collection
 * @package Magister
 */
class Collection implements Arrayable, \ArrayAccess, \Countable, \IteratorAggregate, Jsonable
{
    /**
     * The items in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new Collection instance.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = (array) $items;
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param mixed $items
     * @return static
     */
    public static function make($items = null)
    {
        return new static($items);
    }

    /**
     * Put an item in the collection by key.
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        if ($this->has($key))
        {
            return $this->items[$key];
        }
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param mixed $key
     * @return void
     */
    public function forget($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Return all the items inside the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Find a model in the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function find($key, $default = null)
    {
        return array_first($this->items, function($itemKey, $model) use ($key)
        {
            return $itemKey == $key;
        }, $default);
    }

    /**
     * Get the first item from the collection.
     *
     * @param callable $callback
     * @param mixed $default
     * @return mixed|null
     */
    public function first(callable $callback = null, $default = null)
    {
        if (is_null($callback))
        {
            return count($this->items) > 0 ? reset($this->items) : null;
        }

        return array_first($this->items, $callback, $default);
    }

    /**
     * Get the last item from the collection.
     *
     * @return mixed|null
     */
    public function last()
    {
        return count($this->items) > 0 ? end($this->items) : null;
    }

    /**
     * Execute a callback over each item.
     *
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        array_map($callback, $this->items);

        return $this;
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @return static
     */
    public function flatten()
    {
        return new static(array_flatten($this->items));
    }

    /**
     * Get an array with the values of a given key.
     *
     * @param string $value
     * @param string $key
     * @return array
     */
    public function lists($value, $key = null)
    {
        return array_pluck($this->items, $value, $key);
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed|null
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed|null
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param mixed $value
     * @return void
     */
    public function prepend($value)
    {
        array_unshift($this->items, $value);
    }

    /**
     * Get one or more items randomly from the collection.
     *
     * @param int $amount
     * @return mixed
     */
    public function random($amount = 1)
    {
        if ($this->isEmpty()) return;

        $keys = array_rand($this->items, $amount);

        return is_array($keys) ? array_intersect_key($this->items, array_flip($keys)) : $this->items[$keys];
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Shuffle the items in the collection.
     *
     * @return $this
     */
    public function shuffle()
    {
        shuffle($this->items);

        return $this;
    }

    /**
     * Slice the underlying collection array.
     *
     * @param int $offset
     * @param int $length
     * @param bool $preserveKeys
     * @return static
     */
    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(array_slice($this->items, $offset, $length, $preserveKeys));
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param int $size
     * @param bool $preserveKeys
     * @return static
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk)
        {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * Sort through each item with a callback.
     *
     * @param callable $callback
     * @return $this
     */
    public function sort(callable $callback)
    {
        uasort($this->items, $callback);

        return $this;
    }

    /**
     * Take the first or last {$limit} items.
     *
     * @param int $limit
     * @return static
     */
    public function take($limit = null)
    {
        if ($limit < 0) return $this->slice($limit, abs($limit));

        return $this->slice(0, $limit);
    }

    /**
     * Return only unique items from the collection array.
     *
     * @return static
     */
    public function unique()
    {
        return new static(array_unique($this->items));
    }

    /**
     * Reset the keys on the underlying array.
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function($value)
        {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->put($key, $value);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Determine if an item exists at a given offset.
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->forget($key);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}