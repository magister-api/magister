<?php

namespace Magister\Models\Grade;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Translation\ShouldBeTranslatable;

/**
 * Class Grade.
 */
class Grade extends Model implements ShouldBeTranslatable
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['DatumIngevoerd'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.grade');
    }

    /**
     * Define a relationship.
     *
     * @return mixed
     */
    public function info()
    {
        return $this->hasOne('Magister\Models\Grade\Info', 'cijfer', 'CijferKolom.Id');
    }
}
