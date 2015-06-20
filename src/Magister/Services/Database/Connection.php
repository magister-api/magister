<?php
namespace Magister\Services\Database;

use Magister\Services\Database\Query\Builder;
use Magister\Services\Database\Query\Processors\Processor;
use GuzzleHttp\Client;
use Closure;

/**
 * Class Connection
 * @package Magister
 */
class Connection implements ConnectionInterface
{
    /**
     * The active connection.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The query processor implementation.
     *
     * @var \Magister\Services\Database\Query\Processors\Processor
     */
    protected $processor;

    /**
     * The execution time.
     *
     * @var float
     */
    protected $time;

    /**
     * Create a new Connection instance.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->useDefaultProcessor();
    }

    /**
     * Start a query against the server.
     *
     * @param string $url
     * @return \Magister\Services\Database\Query\Builder
     */
    public function url($url)
    {
        $processor = $this->getProcessor();

        $query = new Builder($this, $processor);

        return $query->from($url);
    }

    /**
     * Run a select statement against the server.
     *
     * @param string $url
     * @param array $bindings
     * @return mixed
     */
    public function select($url, $bindings = [])
    {
        return $this->run($url, $bindings, function ($me, $url, $bindings)
        {
            $url = $me->prepareBindings($bindings, $url);

            $statement = $me->getClient()->get($url);

            return json_decode($statement->getBody(), true);
        });
    }

    /**
     * Run a statement and log its execution context.
     *
     * @param array $bindings
     * @param \Closure $callback
     * @return mixed
     */
    public function run($url, $bindings, Closure $callback)
    {
        $start = microtime(true);

        $result = $callback($this, $url, $bindings);

        $this->time = $this->getElapsedTime($start);

        return $result;
    }

    /**
     * Prepare the query bindings for execution.
     *
     * @param array $bindings
     * @param string $url
     * @return array
     */
    public function prepareBindings(array $bindings, $url)
    {
        foreach ($bindings as $key => $value)
        {
            $url = str_replace(':' . $key, $value, $url);
        }

        return $url;
    }

    /**
     * Set the default processor.
     *
     * @return \Magister\Services\Database\Query\Processors\Processor
     */
    public function setDefaultProcessor()
    {
        return new Processor();
    }

    /**
     * Use the default processor.
     *
     * @return void
     */
    public function useDefaultProcessor()
    {
        $this->processor = $this->setDefaultProcessor();
    }

    /**
     * Set the processor used by the connection.
     *
     * @param \Magister\Services\Database\Query\Processors\Processor $processor
     * @return void
     */
    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Get the processor used by the connection.
     *
     * @return \Magister\Services\Database\Query\Processors\Processor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * Get the current client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the elapsed time since a given starting point.
     *
     * @param int $start
     * @return float
     */
    protected function getElapsedTime($start)
    {
        return round((microtime(true) - $start) * 1000, 2);
    }
}