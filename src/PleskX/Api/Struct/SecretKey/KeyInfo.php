<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\SecretKey;

class KeyInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $key;

    /** @var string */
    public $ipAddress;

    /** @var string */
    public $description;

    /** @var string */
    public $login;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'key',
            'ip_address',
            'description',
            'login',
        ]);
    }
}