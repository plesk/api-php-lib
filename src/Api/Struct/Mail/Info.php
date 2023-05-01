<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Mail;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $name;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
        ]);
    }
}
