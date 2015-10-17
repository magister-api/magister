<?php

namespace Magister\Models\Message;

use Config;
use Magister\Services\Database\Elegant\Model;
use Magister\Services\Contracts\Translation\ShouldBeTranslatable;

/**
 * Class Grade.
 */
class Message extends Model implements ShouldBeTranslatable
{
    /**
     * Get the url associated with the model.
     *
     * @return string
     */
    public function getUrl()
    {
        return Config::get('url.message');
    }
}
