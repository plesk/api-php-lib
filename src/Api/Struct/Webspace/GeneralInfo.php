<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $guid;

    /** @var integer */
    public $realSize;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'guid',
            'real_size',
        ]);
    }
}
