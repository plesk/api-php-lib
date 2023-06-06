<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Certificate;

use PleskX\Api\AbstractStruct;

class PoolInfo extends AbstractStruct
{
    /** @var string */
    public $name;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['name' => 'name'],
        ]);
    }
}