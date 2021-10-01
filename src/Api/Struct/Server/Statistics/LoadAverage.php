<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class LoadAverage extends Struct
{
    public float $load1min;
    public float $load5min;
    public float $load15min;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->load1min = $apiResponse->l1 / 100.0;
        $this->load5min = $apiResponse->l5 / 100.0;
        $this->load15min = $apiResponse->l15 / 100.0;
    }
}
