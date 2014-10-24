<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

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
