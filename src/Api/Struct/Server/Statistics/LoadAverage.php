<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\AbstractStruct;

class LoadAverage extends AbstractStruct
{
    public float $load1min;
    public float $load5min;
    public float $load15min;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->load1min = $apiResponse->l1 / 100.0;
        $this->load5min = $apiResponse->l5 / 100.0;
        $this->load15min = $apiResponse->l15 / 100.0;
    }
}
