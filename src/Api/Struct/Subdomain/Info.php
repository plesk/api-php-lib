<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Subdomain;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $parent;
    public string $name;
    public array $properties;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->properties = [];
        $this->initScalarProperties($apiResponse, [
            'id',
            'parent',
            'name',
        ]);
        foreach ($apiResponse->property ?? [] as $propertyInfo) {
            $this->properties[(string) $propertyInfo->name] = (string) $propertyInfo->value;
        }
    }
}
