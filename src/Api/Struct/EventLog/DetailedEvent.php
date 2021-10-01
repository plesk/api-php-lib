<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\EventLog;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class DetailedEvent extends Struct
{
    public int $id;
    public string $type;
    public int $time;
    public string $class;
    public string $objectId;
    public string $user;
    public string $host;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'type',
            'time',
            'class',
            ['obj_id' => 'objectId'],
            'user',
            'host',
        ]);
    }
}
