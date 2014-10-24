<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Reseller;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['pname' => 'personalName'],
            'login',
        ]);
    }
}