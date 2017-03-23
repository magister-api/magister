<?php

namespace Magister\Models;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Appointment.
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

    /**
     * Grab the appointments of today.
     *
     * @return mixed
     */
    public static function today()
    {
        $today = (new DateTime())->format('Y-m-d');

        return static::where('status', 1)->where('van', $today)->where('tot', $today)->get();
    }
}
