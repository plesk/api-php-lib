<?php

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class HostingSetting extends AbstractStruct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];
		
		if( !isset( $apiResponse->vrt_hst->property ) ) {
			return;
		}
		
        foreach ($apiResponse->vrt_hst->property as $propertyInfo) {
            $this->properties[$propertyInfo->name] = $propertyInfo->value;
        }
    }
}