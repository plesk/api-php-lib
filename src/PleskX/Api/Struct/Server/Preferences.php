<?php

class PleskX_Api_Struct_Server_Preferences extends PleskX_Api_Struct_Abstract
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