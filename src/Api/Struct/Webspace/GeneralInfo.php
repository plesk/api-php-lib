<?php
// Copyright 1999-2019. Plesk International GmbH.

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
