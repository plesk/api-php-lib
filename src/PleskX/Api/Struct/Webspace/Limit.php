<?php

namespace PleskX\Api\Struct\Webspace;

class Limit extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];

		if( !isset( $apiResponse->vrt_hst->property ) ) {
			return;
		}
		
        foreach ($apiResponse->limit as $propertyInfo) {
            $this->properties[reset( $propertyInfo->name)] = reset( $propertyInfo->value );
        }
    }
}