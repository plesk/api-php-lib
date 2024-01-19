<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\AbstractStruct;

class Other extends AbstractStruct
{
    public string $cpu;
    public int $uptime;
    public bool $insideVz;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'cpu',
            'uptime',
            ['inside_vz' => 'insideVz'],
        ]);
    }
}
