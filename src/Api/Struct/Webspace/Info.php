<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public string $guid;
    public string $name;

    public function __construct(XmlResponse $apiResponse, string $name = '')
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
        $this->name = $name;
    }
}
