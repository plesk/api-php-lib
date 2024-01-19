<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Site;

use PleskX\Api\AbstractStruct;

class HostingInfo extends AbstractStruct
{
    public array $properties = [];
    public string $ipAddress;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        foreach ($apiResponse->vrt_hst->property ?? [] as $property) {
            $this->properties[(string) $property->name] = (string) $property->value;
        }

        if (!is_null($apiResponse->vrt_hst)) {
            $this->initScalarProperties($apiResponse->vrt_hst, [
                'ip_address',
            ]);
        }
    }
}
