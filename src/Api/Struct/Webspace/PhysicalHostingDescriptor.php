<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class PhysicalHostingDescriptor extends AbstractStruct
{
    public array $properties;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->descriptor->property ?? [] as $propertyInfo) {
            $this->properties[(string) $propertyInfo->name] = new HostingPropertyInfo($propertyInfo);
        }
    }
}
