<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\AbstractStruct;

class DiskSpace extends AbstractStruct
{
    public int $total;
    public int $used;
    public int $free;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
        ]);
    }
}
