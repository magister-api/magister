<?php

namespace Magister\Models;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Course
 * @package Magister
 */
class Course extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['begindatum', 'einddatum'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.course');
    }
}
