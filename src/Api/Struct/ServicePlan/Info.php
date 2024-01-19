<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\ServicePlan;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $name;
    public string $guid;
    public string $externalId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'guid',
            'external-id',
        ]);
    }
}
