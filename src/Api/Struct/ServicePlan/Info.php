<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\ServicePlan;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public string $name;
    public string $guid;
    public string $externalId;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'guid',
            'external-id',
        ]);
    }
}
