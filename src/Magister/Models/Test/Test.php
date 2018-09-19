<?php

namespace Magister\Models\Test;

use Magister\Magister;

class Test
{
    /**
     * The API instance.
     *
     * @var SkyLines
     */
    protected $api;

    /**
     * Construct the model.
     *
     * @param SkyLines $api
     */
    public function __construct(Magister $api)
    {
        $this->api = $api;
    }

    /**
     * Say something!
     *
     * @param string $string
     *
     * @return string
     */
    public function say($string)
    {
        return $string;
    }
}
