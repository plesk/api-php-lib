<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\DatabaseServer;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public string $host;
    public int $port;
    public string $type;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'host',
            'port',
            'type',
        ]);
    }
}
