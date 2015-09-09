<?php

namespace Magister;

use Magister\Services\Config\FileLoader;
use Magister\Services\Container\Container;
use Magister\Services\Contracts\Foundation\Application as ApplicationContract;
use Magister\Services\Filesystem\Filesystem;
use Magister\Services\Foundation\Http\Kernel;
use Magister\Services\Foundation\ProviderRepository;
use Magister\Services\Support\ServiceProvider;

/**
 * Class Magister.
 */
class Magister extends Container implements ApplicationContract
{
    /**
     * The Magister version.
     *
     * @var string
     */
    const VERSION = '2.0.2';

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * Create a new Magister instance.
     *
     * @param string $school
     * @param string $username
     * @param string $password
     */
    public function __construct($school, $username = null, $password = null)
    {
        $kernel = new Kernel($this);

        $this->registerBaseBindings($school, $username, $password);

        $this->bindPathsInContainer();

        $kernel->bootstrap();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @param string $school
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    protected function registerBaseBindings($school, $username, $password)
    {
        $this->bind('app', $this);

        $this->setSchool($school);

        if ($username && $password) {
            $this->setCredentials($username, $password);
        }
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param array $bootstrappers
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
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
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
     * Get the base path of the Magister installation.
     *
     * @return string
     */
    public function basePath()
    {
        return realpath(__DIR__);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath().DIRECTORY_SEPARATOR.'Config';
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     *
     * @return void
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
    }

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * Get the configuration loader instance.
     *
     * @return \Magister\Services\Config\LoaderInterface
     */
    public function getConfigLoader()
    {
        return new FileLoader(new Filesystem(), $this['path.config']);
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerProviders()
    {
        (new ProviderRepository($this))->load($this->config['app.providers']);
    }

    /**
     * Register a service provider with the application.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     * @param array                                      $options
     *
     * @return \Magister\Services\Support\ServiceProvider
     */
    public function register(ServiceProvider $provider, $options = [])
    {
        if (is_string($provider)) {
            $provider = $this->resolveProviderClass($provider);
        }

        $provider->register();

        foreach ($options as $key => $value) {
            $this[$key] = $value;
        }

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param \Magister\Services\Support\ServiceProvider|string $provider
     *
     * @return \Magister\Services\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return array_first($this->serviceProviders, function ($key, $value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     *
     * @return \Magister\Services\Support\ServiceProvider
     */
    public function resolveProviderClass($provider)
    {
        return new $provider($this);
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

    /**
     * Mark the given provider as registered.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     *
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;
    }
}
