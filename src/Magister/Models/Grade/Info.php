<?php

namespace Magister\Models\Grade;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Info
 * @package Magister
 */
class Info extends Model
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.info');
    }
}
