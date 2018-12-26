<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

class Preferences extends \PleskX\Api\Struct
{
    /** @var integer */
    public $statTtl;

    /** @var integer */
    public $trafficAccounting;

    /** @var integer */
    public $restartApacheInterval;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'stat_ttl',
            'traffic_accounting',
            'restart_apache_interval',
        ]);
    }
}
