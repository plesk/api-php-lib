<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Site;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class HostingInfo extends Struct
{
    public array $properties = [];
    public string $ipAddress;

    public function __construct(XmlResponse $apiResponse)
    {
        foreach ($apiResponse->vrt_hst->property as $property) {
            $this->properties[(string) $property->name] = (string) $property->value;
        }
        $this->_initScalarProperties($apiResponse->vrt_hst, [
            'ip_address',
        ]);
    }
}
