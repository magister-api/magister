<?php
namespace Magister\Models\Enrollment;

use Config;
use DateTime;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Enrollment
 * @package Magister
 */
class Enrollment extends Model
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
        return Config::get('url.enrollment');
    }

    /**
     * Grab the current enrollment from the collection.
     *
     * @return mixed
     */
    public static function current()
    {
        $today = (new DateTime)->format('Y-m-d');

        return static::where('geenToekomstige', false)->where('peildatum', $today)->first();
    }

    /**
     * Define a relationship.
     *
     * @return mixed
     */
    public function counsellors()
    {
        return $this->hasMany('Magister\Models\Enrollment\Counsellor', 'group', 'Groep.Id');
    }
}