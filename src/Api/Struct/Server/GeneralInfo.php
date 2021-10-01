<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class GeneralInfo extends Struct
{
    public string $serverName;
    public string $serverGuid;
    public string $mode;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'server_name',
            'server_guid',
            'mode',
        ]);
    }
}
