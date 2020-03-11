<?php

namespace PleskX\Api\Struct\Webspace;

class Limit extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->limit as $propertyInfo) {
            $this->properties[(string)$propertyInfo->name] = $propertyInfo->value;
        }
    }
}