<?php

namespace Magister\Models\Profile;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Translation\ShouldBeTranslatable;

/**
 * Class Address.
 */
class Address extends Model implements ShouldBeTranslatable
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
