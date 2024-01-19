<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class PhpSettings extends AbstractStruct
{
    public array $properties;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->webspace->get->result->data->{'php-settings'}->setting ?? [] as $setting) {
            $this->properties[(string) $setting->name] = (string) $setting->value;
        }
    }
}
