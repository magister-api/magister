<?php

namespace Magister\Models;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Appointment
 * @package Magister
 */
class Appointment extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['Start', 'Einde'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.appointment');
    }
}
