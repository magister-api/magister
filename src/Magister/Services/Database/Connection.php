<?php
namespace Magister\Services\Database;

use Closure;
use Exception;
use GuzzleHttp\Client;
use Magister\Services\Database\Query\Builder;
use Magister\Services\Database\Query\Processors\Processor;

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
     * Create a new connection instance.
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
     * @param string $query
     * @return \Magister\Services\Database\Query\Builder
     */
    public function query($query)
    {
        $processor = $this->getProcessor();

        $builder = new Builder($this, $processor);

        return $builder->from($query);
    }

    /**
     * Run a select statement against the server.
     *
     * @param string $query
     * @param array $bindings
     * @return mixed
     */
    public function select($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($me, $query, $bindings) {
            list($query, $bindings) = $me->prepareBindings($query, $bindings);

            // For select statements, we'll simply execute the query and return an array
            // of the result set. Each element in the array will be a single
            // row from the response, and will either be an array or objects.
            $statement = $me->getClient()->get($query, ['query' => $bindings]);

            return $statement->json();
        });
    }

    /**
     * Prepare the query bindings for execution.
     *
     * @param string $query
     * @param array $bindings
     * @return array
     */
    public function prepareBindings($query, array $bindings)
    {
        foreach ($bindings as $key => $value) {
            $search = ':' . $key;

            if (strpos($query, $search) !== false) {
                $query = str_replace($search, $value, $query);

                unset($bindings[$key]);
            }
        }

        return [$query, $bindings];
    }

    /**
     * Run a statement and log its execution context.
     *
     * @param string $query
     * @param array $bindings
     * @param \Closure $callback
     * @return mixed
     */
    public function run($query, $bindings, Closure $callback)
    {
        $start = microtime(true);

        $result = $this->runQueryCallback($query, $bindings, $callback);

        // Once we have run the query we will calculate the time that it took to run and
        // then log the query, bindings, and execution time so we will report them on
        // the event that the developer needs them. We'll log time in milliseconds.
        $this->time = $this->getElapsedTime($start);

        return $result;
    }

    /**
     * Run a SQL statement.
     *
     * @param string $query
     * @param array $bindings
     * @param \Closure $callback
     * @return mixed
     * @throws \Magister\Services\Database\QueryException
     */
    protected function runQueryCallback($query, $bindings, Closure $callback)
    {
        try {
            $result = $callback($this, $query, $bindings);
        } catch (Exception $e) {
            // If an exception occurs when attempting to run a request, we'll format the error
            // message to include the bindings, which will make this exception a
            // lot more helpful to the developer instead of just the client's errors.
            list($query, $bindings) = $this->prepareBindings($query, $bindings);

            throw new QueryException($query, $bindings, $e);
        }

        return $result;
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
