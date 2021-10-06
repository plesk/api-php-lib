<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Mail;

use PleskX\Api\Struct;

class GeneralInfo extends Struct
{
    public int $id;
    public string $name;
    public string $description;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'description',
        ]);
    }
}
