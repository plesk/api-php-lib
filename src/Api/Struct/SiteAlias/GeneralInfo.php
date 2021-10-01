<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class GeneralInfo extends Struct
{
    public string $name;
    public string $asciiName;
    public string $status;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'ascii-name',
            'status',
        ]);
    }
}
