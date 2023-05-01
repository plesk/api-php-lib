<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Site;

use PleskX\Api\AbstractStruct;

class HostingInfo extends AbstractStruct
{
    public array $properties = [];
    public string $ipAddress;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        foreach ($apiResponse->vrt_hst->property as $property) {
            $this->properties[(string) $property->name] = (string) $property->value;
        }
        $this->initScalarProperties($apiResponse->vrt_hst, [
            'ip_address',
        ]);
    }
}
