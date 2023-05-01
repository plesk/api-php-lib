<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class LimitInfo extends AbstractStruct
{
    public string $name;
    public string $type;
    public string $label;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'name',
            'type',
            'label',
        ]);
    }
}
