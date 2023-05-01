<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\AbstractStruct;

class SessionPreferences extends AbstractStruct
{
    public int $loginTimeout;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'login_timeout',
        ]);
    }
}
