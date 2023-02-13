<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Struct\EventLog;

use PleskX\Api\AbstractStruct;

class Event extends AbstractStruct
{
    public string $type;
    public int $time;
    public string $class;
    public string $id;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'type',
            'time',
            'class',
            'id',
        ]);
    }
}
