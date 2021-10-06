<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\Struct;

class Other extends Struct
{
    public string $cpu;
    public int $uptime;
    public bool $insideVz;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'cpu',
            'uptime',
            ['inside_vz' => 'insideVz'],
        ]);
    }
}
