<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class LoadAverage extends \PleskX\Api\Struct
{
    /** @var float */
    public $load1min;

    /** @var float */
    public $load5min;

    /** @var float */
    public $load15min;

    public function __construct($apiResponse)
    {
        $this->load1min = $apiResponse->l1 / 100.0;
        $this->load5min = $apiResponse->l5 / 100.0;
        $this->load15min = $apiResponse->l15 / 100.0;
    }
}
