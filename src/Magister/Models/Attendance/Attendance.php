<?php

namespace Magister\Models\Attendance;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Attendance
 * @package Magister
 */
class Attendance extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['Start', 'Eind', 'Afspraak.Start', 'Afspraak.Einde'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.attendance');
    }
}
