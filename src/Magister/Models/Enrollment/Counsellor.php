<?php

namespace Magister\Models\Enrollment;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Counsellor
 * @package Magister
 */
class Counsellor extends Model
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.counsellor');
    }
}
