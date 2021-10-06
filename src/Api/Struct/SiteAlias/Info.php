<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

use PleskX\Api\Struct;

class Info extends Struct
{
    public string $status;
    public int $id;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'status',
        ]);
    }
}
