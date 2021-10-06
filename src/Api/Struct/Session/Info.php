<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Session;

use PleskX\Api\Struct;

class Info extends Struct
{
    public string $id;
    public string $type;
    public string $ipAddress;
    public string $login;
    public string $loginTime;
    public string $idle;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'type',
            'ip-address',
            'login',
            'login-time',
            'idle',
        ]);
    }
}
