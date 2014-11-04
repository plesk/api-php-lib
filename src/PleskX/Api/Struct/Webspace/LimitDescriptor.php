<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Webspace;

class LimitDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $limits;

    public function __construct($apiResponse)
    {
        $this->limits = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->limits[(string)$propertyInfo->name] = new LimitInfo($propertyInfo);
        }
    }
}