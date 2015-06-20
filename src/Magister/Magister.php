<?php
namespace Magister;

use Magister\Services\Container\Container;
use Magister\Foundation\Kernel;
use Magister\Services\Support\ServiceProvider;
use Magister\Foundation\ProviderRepository;

/**
 * Class Magister
 * @package Magister
 */
class Magister extends Container
{
    /**
     * The Magister version.
     *
     * @var string
     */
    const VERSION = '2.0.0';

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
        $this->bind('app', $this);

        $this->setSchool($school);
        $this->setCredentials($username, $password);

        (new Kernel($this))->bootstrap();
    }

    /**
     * Set the school.
     *
     * @param string $school
     * @return void
     */
    public function setSchool($school)
    {
        $this->bind('url', "https://$school.magister.net/api/");
    }

    /**
     * Set the credentials.
     *
     * @param string $username
     * @param string $password
     * @return void
     */
    public function setCredentials($username, $password)
    {
        $this->bind('credentials', [
            'form_params' => [
                'Gebruikersnaam' => $username,
                'Wachtwoord' => $password
            ]
        ]);
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
     * Get the base path of the Magister installation.
     *
     * @return string
     */
    public function basePath()
    {
        return realpath(__DIR__);
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param array $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        foreach ($bootstrappers as $bootstrapper)
        {
            (new $bootstrapper)->bootstrap($this);
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
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) return;

        array_walk($this->serviceProviders, function($p)
        {
            $this->bootProvider($p);
        });

        $this->booted = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     * @return void
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot'))
        {
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
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerProviders()
    {
        (new ProviderRepository($this))->load($this->config['application.providers']);
    }

    /**
     * Register a service provider with the application.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     * @param array $options
     * @return $this
     */
    public function register(ServiceProvider $provider, $options = [])
    {
        if (is_string($provider))
        {
            $provider = $this->resolveProviderClass($provider);
        }

        $provider->register();

        foreach ($options as $key => $value)
        {
            $this[$key] = $value;
        }

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param \Magister\Services\Support\ServiceProvider|string $provider
     * @return \Magister\Services\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return array_first($this->serviceProviders, function($key, $value) use ($name)
        {
            return $value instanceof $name;
        });
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     * @return \Magister\Services\Support\ServiceProvider
     */
    public function resolveProviderClass($provider)
    {
        return new $provider($this);
    }

    /**
     * Mark the given provider as registered.
     *
     * @param \Magister\Services\Support\ServiceProvider $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;
    }
}