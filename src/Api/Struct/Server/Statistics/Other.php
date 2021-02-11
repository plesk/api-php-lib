<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Other extends \PleskX\Api\Struct
{
    /** @var string */
    public $cpuName;

    /** @var int */
    public $serverUptime;

    /** @var bool */
    public $insideVirtuell;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cpu' => 'cpuName'],
            ['uptime' => 'serverUptime'],
            ['inside_vz' => 'insideVirtuell'],
        ]);
    }
}
