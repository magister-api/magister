<?php
namespace Magister\Models;

use Magister\Services\Database\Elegant\Model;
use Config;

/**
 * Class Grade
 * @package Magister
 */
class Grade extends Model
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('locations.grades');
    }

    /**
     * Define a relationship.
     *
     * @return mixed
     */
    public function info()
    {
        return $this->hasOne('Magister\Models\Info', 'CijferId');
    }
}