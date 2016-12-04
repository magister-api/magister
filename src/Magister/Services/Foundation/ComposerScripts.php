<?php

namespace Magister\Services\Foundation;

use Magister\Magister;
use Composer\Script\Event;

class ComposerScripts
{
    /**
     * The Magister application instance.
     *
     * @var \Magister\Magister
     */
    protected static $magister;

    /**
     * Handle the post-install Composer event.
     *
     * @param \Composer\Script\Event $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::generateKey();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param \Composer\Script\Event $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        static::generateKey();
    }

    /**
     * Generate a safe key for session encryption.
     *
     * @return void
     */
    protected static function generateKey()
    {
        static::$magister = new Magister('foo');

        $key = static::generateRandomKey();

        static::setKeyInEnvironmentFile($key);
    }

    /**
     * Set the application key in the config file.
     *
     * @param string $key
     * @return void
     */
    protected static function setKeyInEnvironmentFile($key)
    {
        file_put_contents(static::$magister->configPath().'/app.php', str_replace(
            '\'key\' => \''.static::$magister['config']['app.key'].'\',',
            '\'key\' => \''.$key.'\',',
            file_get_contents(static::$magister->configPath().'/app.php')
        ));
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected static function generateRandomKey()
    {
        return str_random(32);
    }
}
