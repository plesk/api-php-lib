<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public string $name;
    public string $asciiName;
    public string $status;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'name',
            'ascii-name',
            'status',
        ]);
    }
}
