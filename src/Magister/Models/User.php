<?php
namespace Magister\Models;

use Magister\Services\Auth\Authenticable;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Support\Contracts\Authenticable as AuthenticableContract;
use Config;

/**
 * Class User
 * @package Magister
 */
class User extends Model implements AuthenticableContract
{
    use Authenticable;

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('locations.user');
    }

    /**
     * Get the user profile details.
     *
     * @return \Magister\Services\Database\Elegant\Model|static|null
     */
    public static function profile()
    {
        return static::get();
    }
}