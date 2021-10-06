<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\Struct;

class DataInfo extends Struct
{
    public string $name;
    public string $header;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'header',
        ]);
    }
}
