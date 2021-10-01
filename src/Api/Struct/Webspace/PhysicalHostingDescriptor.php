<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class PhysicalHostingDescriptor extends Struct
{
    public array $properties;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->properties[(string) $propertyInfo->name] = new HostingPropertyInfo($propertyInfo);
        }
    }
}
