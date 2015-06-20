<?php
namespace Magister\Services\Auth;

use GuzzleHttp\Exception\ClientException;
use Magister\Services\Support\Contracts\UserProvider;
use GuzzleHttp\Client;

/**
 * Class ElegantUserProvider
 * @package Magister
 */
class ElegantUserProvider implements UserProvider
{
    /**
     * The Elegant user model.
     *
     * @var string
     */
    protected $model;

    /**
     * The active connection.
     *
     * @var \Magister\Services\Http\Http
     */
    protected $client;

    /**
     * Create a new ElegantUserProvider instance.
     *
     * @param \GuzzleHttp\Client $client
     * @param string $model
     */
    public function __construct(Client $client, $model)
    {
        $this->client = $client;
        $this->model = $model;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $response = $this->client->post('sessie', $credentials);

        return $this->createModel()->newQuery()->first();
    }

    /**
     * Clear the cookies from the client.
     *
     * @return void
     */
    public function clearCookies()
    {
        $this->client->getConfig('cookies')->clear();
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }
}