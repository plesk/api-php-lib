<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Database;

use PleskX\Api\AbstractStruct;

class UserInfo extends AbstractStruct
{
    public int $id;
    public string $login;
    public int $dbId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'login',
            'db-id',
        ]);
    }
}
