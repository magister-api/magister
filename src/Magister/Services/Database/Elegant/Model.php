<?php
namespace Magister\Services\Database\Elegant;

use Magister\Services\Support\Contracts\Arrayable;
use Magister\Services\Support\Contracts\Jsonable;
use Magister\Services\Database\Query\Builder as QueryBuilder;
use Magister\Services\Database\ConnectionResolverInterface as Resolver;
use Magister\Services\Database\Elegant\Relations\HasOne;
use Magister\Exceptions\LogicException;
use Magister\Services\Database\Elegant\Relations\Relation;

/**
 * Class Model
 * @package Magister
 */
abstract class Model implements Arrayable, \ArrayAccess, Jsonable
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * The url associated with the model.
     *
     * @var string
     */
    protected $url;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * The connection resolver instance.
     *
     * @var \Magister\Services\Database\ConnectionResolverInterface
     */
    protected static $resolver;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The loaded relationships for the model.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Create a new Model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->fill($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value)
        {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Set the array of model attributes without checking.
     *
     * @param array $attributes
     * @return void
     */
    public function setRawAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        if ($inAttributes)
        {
            return $this->attributes[$key];
        }

        if (array_key_exists($key, $this->relations))
        {
            return $this->relations[$key];
        }

        if (method_exists($this, $key))
        {
            return $this->getRelationshipFromMethod($key);
        }
    }

    /**
     * Get a relationship value from a method.
     *
     * @param string $method
     * @return mixed
     * @throws \Magister\Exceptions\LogicException
     */
    protected function getRelationshipFromMethod($method)
    {
        $relations = $this->$method();

        if ( ! $relations instanceof Relation)
        {
            throw new LogicException('Relationship method must return an object of type ' . 'Magister\Services\Database\Elegant\Relations\Relation');
        }

        return $this->relations[$method] = $relations->getResults();
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get all of the items from the model.
     *
     * @return \Magister\Services\Database\Elegant\Collection
     */
    public static function all()
    {
        $instance = new static;

        return $instance->newQuery()->get();
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @return \Magister\Services\Database\Elegant\Collection|static|null
     */
    public static function find($id)
    {
        return static::query()->find($id);
    }

    /**
     * Begin querying the model on a given connection.
     *
     * @param string|null $connection
     * @return \Magister\Services\Database\Elegant\Builder
     */
    public static function on($connection = null)
    {
        $instance = new static;

        $instance->setConnection($connection);

        return $instance->newQuery();
    }

    /**
     * Define a relationship.
     *
     * @param string $related
     * @param string $localKey
     * @return \Magister\Services\Database\Elegant\Relation
     */
    public function hasOne($related, $localKey)
    {
        $instance = new $related;

        return new HasOne($instance->newQuery(), $this, $localKey);
    }

    /**
     * Create a new instance of the given model.
     *
     * @param array $attributes
     * @return static
     */
    public function newInstance($attributes = [])
    {
        return new static((array) $attributes);
    }

    /**
     * Create a new model instance.
     *
     * @param array $attributes
     * @param string|null $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = $this->newInstance();

        $model->setRawAttributes((array) $attributes);

        $model->setConnection($connection ?: $this->connection);

        return $model;
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param array $items
     * @param string|null $connection
     * @return \Magister\Services\Database\Elegant\Collection
     */
    public static function hydrate(array $items, $connection = null)
    {
        $instance = (new static)->setConnection($connection);

        $items = array_map(function ($item) use ($instance)
        {
            return $instance->newFromBuilder($item);
        }, $items);

        return $instance->newCollection($items);
    }

    /**
     * Begin querying the model.
     *
     * @return \Magister\Services\Database\Elegant\Builder
     */
    public static function query()
    {
        return (new static)->newQuery();
    }

    /**
     * Get a new query builder.
     *
     * @return \Magister\Services\Database\Elegant\Builder
     */
    public function newQuery()
    {
        $builder = $this->newSchemaBuilder(
            $this->newQueryBuilder()
        );

        return $builder->setModel($this);
    }

    /**
     * Create a new schema builder instance.
     *
     * @param \Magister\Services\Database\Query\Builder $query
     * @return \Magister\Services\Database\Elegant\Builder
     */
    public function newSchemaBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Create a new query builder instance.
     *
     * @return \Magister\Services\Database\Query\Builder
     */
    public function newQueryBuilder()
    {
        $connection = $this->getConnection();

        return new QueryBuilder($connection, $connection->getProcessor());
    }

    /**
     * Create a new collection instance.
     *
     * @param array $models
     * @return \Magister\Services\Database\Elegant\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }

    /**
     * Get the connection for the model.
     *
     * @return \Magister\Services\Database\Connection
     */
    public function getConnection()
    {
        return static::resolveConnection($this->connection);
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * Set the connection associated with the model.
     *
     * @param string $name
     * @return $this
     */
    public function setConnection($name)
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * Resolve a connection instance.
     *
     * @param string $connection
     * @return \Magister\Services\Database\Connection
     */
    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection($connection);
    }

    /**
     * Get the connection resolver instance.
     *
     * @return \Magister\Services\Database\ConnectionResolverInterface
     */
    public static function getConnectionResolver()
    {
        return static::$resolver;
    }

    /**
     * Set the connection resolver instance.
     *
     * @param \Magister\Services\Database\ConnectionResolverInterface $resolver
     * @return void
     */
    public static function setConnectionResolver(Resolver $resolver)
    {
        static::$resolver = $resolver;
    }

    /**
     * Set the url associated with the model.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function setUrl()
    {
        throw new \RuntimeException("The Model class does not implement a setDefaultUrl method.");
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param string $key
     * @return void
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Determine if an attribute exists on the model.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $query = $this->newQuery();

        return call_user_func_array([$query, $method], $parameters);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array([$instance, $method], $parameters);
    }
}