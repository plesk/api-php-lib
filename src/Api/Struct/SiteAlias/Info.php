<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $status;
    public int $id;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'status',
        ]);
    }
}
