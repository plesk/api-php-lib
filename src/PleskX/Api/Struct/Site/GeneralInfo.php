<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Site;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $asciiName;

    /** @var string */
    public $guid;

    /** @var string */
    public $description;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'ascii-name',
            'guid',
            'description',
        ]);
    }
}