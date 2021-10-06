<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\ServicePlan;

use PleskX\Api\Struct;

class Info extends Struct
{
    public int $id;
    public string $name;
    public string $guid;
    public string $externalId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'guid',
            'external-id',
        ]);
    }
}
