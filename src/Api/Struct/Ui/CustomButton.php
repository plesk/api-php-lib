<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Ui;

use PleskX\Api\AbstractStruct;

class CustomButton extends AbstractStruct
{
    public int $id;
    public int $sortKey;
    public bool $public;
    public bool $internal;
    public bool $noFrame;
    public string $place;
    public string $url;
    public string $text;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, ['id']);

        if (!is_null($apiResponse->properties)) {
            $this->initScalarProperties($apiResponse->properties, [
                'sort_key',
                'public',
                'internal',
                ['noframe' => 'noFrame'],
                'place',
                'url',
                'text',
            ]);
        }
    }
}
