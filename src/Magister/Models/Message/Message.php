<?php

namespace Magister\Models\Message;

use Config;
use Magister\Services\Database\Elegant\Model;

/**
 * Class Message.
 */
class Message extends Model
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
