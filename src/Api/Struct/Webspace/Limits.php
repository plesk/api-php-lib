<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class Limits extends AbstractStruct
{
    public string $overuse;
    public array $limits;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, ['overuse']);
        $this->limits = [];

        foreach ($apiResponse->limit as $limit) {
            $this->limits[(string) $limit->name] = new Limit($limit);
        }
    }
}
