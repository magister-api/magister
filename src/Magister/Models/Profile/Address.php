<?php

namespace Magister\Models\Profile;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Address
 * @package Magister
 */
class Address extends Model
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.address');
    }
}
