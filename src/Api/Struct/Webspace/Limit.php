<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Limit extends Struct
{
    public string $name;
    public string $value;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'value',
        ]);
    }
}
