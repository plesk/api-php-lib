<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Ip;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $ipAddress;
    public string $netmask;
    public string $type;
    public string $interface;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'ip_address',
            'netmask',
            'type',
            'interface',
        ]);
    }
}
