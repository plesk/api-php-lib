<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public string $serverName;
    public string $serverGuid;
    public string $mode;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'server_name',
            'server_guid',
            'mode',
        ]);
    }
}
