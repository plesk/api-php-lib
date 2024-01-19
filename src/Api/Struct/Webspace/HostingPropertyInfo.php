<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class HostingPropertyInfo extends AbstractStruct
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
