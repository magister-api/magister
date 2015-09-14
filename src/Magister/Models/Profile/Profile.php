<?php

namespace Magister\Models\Profile;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Profile.
 */
class Profile extends Model
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
