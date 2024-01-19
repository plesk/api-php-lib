<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\SecretKey;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $key;
    public string $ipAddress;
    public string $description;
    public string $login;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'key',
            'ip_address',
            'description',
            'login',
        ]);
    }
}
