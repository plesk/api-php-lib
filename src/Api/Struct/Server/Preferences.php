<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Preferences extends Struct
{
    public int $statTtl;
    public int $trafficAccounting;
    public int $restartApacheInterval;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'stat_ttl',
            'traffic_accounting',
            'restart_apache_interval',
        ]);
    }
}
