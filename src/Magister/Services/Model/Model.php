<?php

namespace Magister\Services\Model;

use Magister\Magister;

class Model
{
    protected $api;

    public function __construct(Magister $api)
    {
        $this->api = $api;
    }

    public function all()
    {
        return $this->get($this->retrieveUri('all'));
    }

    protected function retrieveUri()
    {
    }

    protected function get($url)
    {
    }
}
