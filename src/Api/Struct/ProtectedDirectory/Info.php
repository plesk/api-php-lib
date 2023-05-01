<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
        ]);
    }
}
