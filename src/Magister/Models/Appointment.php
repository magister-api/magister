<?php

namespace Magister\Models;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Translation\ShouldBeTranslatable;

/**
 * Class Appointment.
 */
class Appointment extends Model implements ShouldBeTranslatable
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
