<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Swap extends Struct
{
    public int $total;
    public int $used;
    public int $free;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
        ]);
    }
}
