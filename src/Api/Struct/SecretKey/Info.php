<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\SecretKey;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public string $key;
    public string $ipAddress;
    public string $description;
    public string $login;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'key',
            'ip_address',
            'description',
            'login',
        ]);
    }
}
