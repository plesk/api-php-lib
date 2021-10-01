<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class PermissionDescriptor extends Struct
{
    public array $permissions;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->permissions = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->permissions[(string) $propertyInfo->name] = new PermissionInfo($propertyInfo);
        }
    }
}
