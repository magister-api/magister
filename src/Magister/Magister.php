<?php

namespace Magister;

use Magister\Services\Container\Container;
use Magister\Services\Foundation\Kernel;

class Magister extends Container
{
    /**
     *  Holds API version.
     */
    const VERSION = '3.0.0';

    /**
     * Has the api been bootstapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Constructor.
     */
    public function __construct($school, $username, $password)
    {
        $kernel = new Kernel($this);

        $this->registerBindings($school, $username, $password);
        $this->bindPathsInContainer();

        $kernel->bootstrap();
    }

    /**
     *	Returns the version.
     *
     * @return string version number
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Has the function been bootstrapped.
     *
     * @return bool [description]
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    /**
     *	Run all registered bootstrappers.
     *
     * @param  array Registered bootstrappers
     *
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        foreach ($bootstrappers as $bootstrapper) {
            (new $bootstrapper())->bootstrap($this);
        }

        $this->hasBeenBootstrapped = true;
    }

        /**
     * Returns the api's base path.
     *
     * @return string
     */
    public function basePath()
    {
        return realpath(__DIR__);
    }

    /**
     *  Return the path to the api's configuration files.
     *
     * @return [type] [description]
     */
    public function configPath()
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'Config';
    }

    /**
     * Return a model.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Model
     */
    public function __call($name, $arguments)
    {
        return $this->resolveModel($name);
    }

    /**
     *	Register bindings to Container.
     *
     * @return void
     */
    protected function registerBindings($school, $username, $password)
    {
        $this->bind('api', $this);
        $this->setSchool($school);

        if ($username && $password) {
            $this->setCredentials($username, $password);
        }
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        foreach (['base', 'config'] as $path) {
            $this->bind('path.'.$path, $this->{$path.'Path'}());
        }
    }

    /**
     * Resolve a model.
     *
     * @param string $name
     *
     * @return Model
     */
    protected function resolveModel($name)
    {
        $name = ucfirst($name);
        $model = 'Magister\Models\\'.$name.'\\'.$name;

        return new $model($this);
    }

    /**
     * Set the school for every request.
     *
     * @param string $school
     *
     * @return void
     */
    protected function setSchool($school)
    {
        $this->bind('school', $school);
    }

    /**
     * Set the credentials used by the authentication service.
     *
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    protected function setCredentials($username, $password)
    {
        $this->bind('credentials', ['Gebruikersnaam' => $username, 'Wachtwoord' => $password]);
    }
}
