<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Subdomain;

use PleskX\Api\Struct;

class Info extends Struct
{
    public int $id;
    public string $parent;
    public string $name;
    public array $properties;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->properties = [];
        $this->_initScalarProperties($apiResponse, [
            'id',
            'parent',
            'name',
        ]);
        foreach ($apiResponse->property as $propertyInfo) {
            $this->properties[(string) $propertyInfo->name] = (string) $propertyInfo->value;
        }
    }
}
