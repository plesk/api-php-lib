<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Ui;

class CustomButton extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;

    /** @var integer */
    public $sortKey;

    /** @var boolean */
    public $public;

    /** @var boolean */
    public $internal;

    /** @var boolean */
    public $noFrame;

    /** @var string */
    public $place;

    /** @var string */
    public $url;

    /** @var string */
    public $text;

    public function __construct($apiResponse)
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