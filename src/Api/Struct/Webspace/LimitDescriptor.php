<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class LimitDescriptor extends AbstractStruct
{
    public array $limits;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->limits = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->limits[(string) $propertyInfo->name] = new LimitInfo($propertyInfo);
        }
    }
}
