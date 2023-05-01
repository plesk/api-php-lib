<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\DatabaseServer;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $host;
    public int $port;
    public string $type;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'host',
            'port',
            'type',
        ]);
    }
}
