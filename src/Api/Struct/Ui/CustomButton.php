<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Ui;

use PleskX\Api\Struct;

class CustomButton extends Struct
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
        $this->_initScalarProperties($apiResponse, ['id']);
        $this->_initScalarProperties($apiResponse->properties, [
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
