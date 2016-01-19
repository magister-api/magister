<?php

namespace Magister\Models\Message;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Folder
 * @package Magister
 */
class Folder extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['VerstuurdOp', 'Begin', 'Einde'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.folder');
    }

    /**
     * Define a relationship.
     *
     * @return mixed
     */
    public function message()
    {
        return $this->hasOne('Magister\Models\Message\Message', 'message', 'Id');
    }
}
