<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Struct\Mail;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public int $id;
    public string $name;
    public string $description;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'description',
        ]);
    }
}
