<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class LimitDescriptor extends Struct
{
    public array $limits;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->limits = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->limits[(string) $propertyInfo->name] = new LimitInfo($propertyInfo);
        }
    }
}
