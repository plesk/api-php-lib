<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class LoadAvg extends \PleskX\Api\Struct
{
    /** @var int */
    public $load1min;

    /** @var int */
    public $load5min;

    /** @var int */
    public $load15min;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['l1' => 'load1min'],
            ['l5' => 'load5min'],
            ['l15' => 'load15min'],
        ]);
    }
}
