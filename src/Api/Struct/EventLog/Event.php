<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\EventLog;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Event extends Struct
{
    public string $type;
    public int $time;
    public string $class;
    public string $id;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'type',
            'time',
            'class',
            'id',
        ]);
    }
}
