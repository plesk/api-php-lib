<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Memory extends \PleskX\Api\Struct
{
    /** @var int */
    public $totalMemory;

    /** @var int */
    public $usedMemory;

    /** @var int */
    public $freeMemory;

    /** @var int */
    public $sharedMemory;

    /** @var int */
    public $bufferMemory;

    /** @var int */
    public $cachedMemory;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['total' => 'totalMemory'],
            ['used' => 'usedMemory'],
            ['free' => 'freeMemory'],
            ['shared' => 'sharedMemory'],
            ['buffer' => 'bufferMemory'],
            ['cached' => 'cachedMemory'],
        ]);
    }
}
