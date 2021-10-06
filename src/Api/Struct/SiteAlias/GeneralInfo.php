<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\Struct;

class GeneralInfo extends Struct
{
    public string $name;
    public string $asciiName;
    public string $status;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'ascii-name',
            'status',
        ]);
    }
}
