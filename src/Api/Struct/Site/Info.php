<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Site;

use PleskX\Api\Struct;

class Info extends Struct
{
    public int $id;
    public string $guid;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}
