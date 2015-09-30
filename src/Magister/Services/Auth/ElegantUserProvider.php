<?php

namespace Magister\Services\Auth;

use GuzzleHttp\Client;
use Magister\Services\Contracts\Auth\UserProvider;

/**
 * Class ElegantUserProvider.
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
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new elegant user provider instance.
     *
     * @param \GuzzleHttp\Client $client
     * @param string             $model
     */
    public function __construct(Client $client, $model)
    {
        $this->client = $client;
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique token.
     *
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByToken()
    {
        return $this->createModel()->newQuery()->first();
    }

    /**
     * Remove the token for the given user in storage.
     *
     * @return void
     */
    public function removeToken()
    {
        $this->client->getDefaultOption('cookies')->clear();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return \Magister\Services\Database\Elegant\Model|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $body = ['body' => $credentials];

        $this->client->get('sessies/huidige');
        $this->client->post('sessies', $body);

        return $this->retrieveByToken();
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Magister\Services\Database\Elegant\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class();
    }
}
