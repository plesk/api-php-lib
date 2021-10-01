<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Ip;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public string $ipAddress;
    public string $netmask;
    public string $type;
    public string $interface;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'ip_address',
            'netmask',
            'type',
            'interface',
        ]);
    }
}
