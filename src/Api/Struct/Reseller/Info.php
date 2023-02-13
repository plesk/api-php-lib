<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Struct\Reseller;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $guid;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}
