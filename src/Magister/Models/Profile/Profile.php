<?php

namespace Magister\Models\Profile;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Translation\ShouldBeTranslatable;

/**
 * Class Profile.
 */
class Profile extends Model implements ShouldBeTranslatable
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.profile');
    }
}
