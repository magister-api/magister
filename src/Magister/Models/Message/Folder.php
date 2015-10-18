<?php

namespace Magister\Models\Message;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Translation\ShouldBeTranslatable;

/**
 * Class Folder.
 */
class Folder extends Model implements ShouldBeTranslatable
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['VerstuurdOp', 'Begin', 'Einde'];

    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.inbox');
    }

    /**
     * Define a relationship.
     *
     * @return mixed
     */
    public function message()
    {
        return $this->hasOne('Magister\Models\Message\Message', 'message', 'Id');
    }
}
