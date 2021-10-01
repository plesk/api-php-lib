<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Reseller;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public string $guid;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}
