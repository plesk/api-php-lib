<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Memory extends \PleskX\Api\Struct
{
    /** @var int */
    public $total;

    /** @var int */
    public $used;

    /** @var int */
    public $free;

    /** @var int */
    public $shared;

    /** @var int */
    public $buffer;

    /** @var int */
    public $cached;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
            'shared',
            'buffer',
            'cached',
        ]);
    }
}
