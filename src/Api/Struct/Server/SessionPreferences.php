<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class SessionPreferences extends Struct
{
    public int $loginTimeout;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'login_timeout',
        ]);
    }
}
