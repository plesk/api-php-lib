<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Webspace;

class PermissionDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $permissions;

    public function __construct($apiResponse)
    {
        $this->permissions = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->permissions[(string)$propertyInfo->name] = new PermissionInfo($propertyInfo);
        }
    }
}