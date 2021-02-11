<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Swap extends \PleskX\Api\Struct
{
    /** @var int */
    public $totalSwap;

    /** @var int */
    public $usedSwap;

    /** @var int */
    public $freeSwap;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['total' => 'totalSwap'],
            ['used' => 'usedSwap'],
            ['free' => 'freeSwap'],
        ]);
    }
}
