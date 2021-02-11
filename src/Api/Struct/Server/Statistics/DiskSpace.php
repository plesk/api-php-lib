<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class DiskSpace extends \PleskX\Api\Struct
{
    /** @var int */
    public $totalDisk;

    /** @var int */
    public $usedDisk;

    /** @var int */
    public $freeDisk;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['total' => 'totalDisk'],
            ['used' => 'usedDisk'],
            ['free' => 'freeDisk'],
        ]);
    }
}
