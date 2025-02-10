<?php
// Copyright 1999-2025. WebPros International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class Limit extends AbstractStruct
{
    public string $name;
    public string $value;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'name',
            'value',
        ]);
    }
}
