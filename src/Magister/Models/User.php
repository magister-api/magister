<?php

namespace Magister\Models;

use Config;
use Magister\Services\Auth\Authenticable;
use Magister\Services\Contracts\Auth\Authenticable as AuthenticableContract;
use Magister\Services\Database\Elegant\Model;

/**
 * Class User.
 */
class User extends Model implements AuthenticableContract
{
    use Authenticable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Persoon.Id';    

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
