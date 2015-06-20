<?php
namespace Magister\Models;

use Magister\Services\Database\Elegant\Model;
use Config;

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
        return Config::get('locations.info');
    }
}