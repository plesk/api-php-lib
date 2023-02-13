<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $guid;
    public string $name;

    public function __construct(\SimpleXMLElement $apiResponse, string $name = '')
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
        $this->name = $name;
    }
}
