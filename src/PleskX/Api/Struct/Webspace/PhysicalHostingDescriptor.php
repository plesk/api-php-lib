<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Webspace;

class PhysicalHostingDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->properties[(string)$propertyInfo->name] = new HostingPropertyInfo($propertyInfo);
        }
    }
}