<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\AbstractStruct;

class DataInfo extends AbstractStruct
{
    public string $name;
    public string $header;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'name',
            'header',
        ]);
    }
}
