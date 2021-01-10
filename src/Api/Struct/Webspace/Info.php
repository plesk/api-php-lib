<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

class Info extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $guid;

    /** @var string */
    public $name;

    public function __construct($apiResponse, $name = '')
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
        $this->name = $name;
    }
}
