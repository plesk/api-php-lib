<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Webspace;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $guid;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'guid',
        ]);
    }
}