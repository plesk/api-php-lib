<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public string $status;
    public int $id;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'status',
        ]);
    }
}
