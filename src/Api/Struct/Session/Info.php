<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Session;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $id;
    public string $type;
    public string $ipAddress;
    public string $login;
    public string $loginTime;
    public string $idle;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'type',
            'ip-address',
            'login',
            'login-time',
            'idle',
        ]);
    }
}
