<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\Struct;

class PhpSettings extends Struct
{
    public array $properties;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->webspace->get->result->data->{'php-settings'}->setting as $setting) {
            $this->properties[(string) $setting->name] = (string) $setting->value;
        }
    }
}
