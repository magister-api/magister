<?php

namespace Magister\Models\Attendance;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Period
 * @package Magister
 */
class Period extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['Start', 'Eind'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.period');
    }
}
