<?php

namespace Magister\Models;

use Config;
use Magister\Services\Auth\Authenticable;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Auth\Authenticable as AuthenticableContract;

/**
 * Class User
 * @package Magister
 */
class User extends Model implements AuthenticableContract
{
    use Authenticable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['Geboortedatum'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.user');
    }

    /**
     * Get the user profile details.
     *
     * @return \Magister\Services\Database\Elegant\Model|static|null
     */
    public static function profile()
    {
        return static::first();
    }
}
