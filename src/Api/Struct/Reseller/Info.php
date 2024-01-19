<?php
// Copyright 1999-2024. WebPros International GmbH.

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
