<?php

namespace PleskX\Api;

class Operator
{

    /** @var \PleskX\Api\Client */
    protected $_client;

    public function __construct($client)
    {
        $this->_client = $client;
    }

}
