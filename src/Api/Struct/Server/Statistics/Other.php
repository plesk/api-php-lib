<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Other extends \PleskX\Api\Struct
{
    /** @var string */
    public $cpu;

    /** @var int */
    public $uptime;

    /** @var bool */
    public $insideVz;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'cpu',
            'uptime',
            ['inside_vz' => 'insideVz'],
        ]);
    }
}
