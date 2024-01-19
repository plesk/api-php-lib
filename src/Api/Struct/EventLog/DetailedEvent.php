<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\EventLog;

use PleskX\Api\AbstractStruct;

class DetailedEvent extends AbstractStruct
{
    public int $id;
    public string $type;
    public int $time;
    public string $class;
    public string $objectId;
    public string $user;
    public string $host;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
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
