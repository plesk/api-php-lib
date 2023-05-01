<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\AbstractStruct;

class Preferences extends AbstractStruct
{
    public int $statTtl;
    public int $trafficAccounting;
    public int $restartApacheInterval;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'stat_ttl',
            'traffic_accounting',
            'restart_apache_interval',
        ]);
    }
}
