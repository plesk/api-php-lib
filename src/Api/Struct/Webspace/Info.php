<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;

class Info extends Struct
{
    public int $id;
    public string $guid;
    public string $name;

    public function __construct(\SimpleXMLElement $apiResponse, string $name = '')
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
        $this->name = $name;
    }
}
