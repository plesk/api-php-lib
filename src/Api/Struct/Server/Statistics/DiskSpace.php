<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class DiskSpace extends \PleskX\Api\Struct
{
    /** @var int */
    public $total;

    /** @var int */
    public $used;

    /** @var int */
    public $free;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
        ]);
    }
}
