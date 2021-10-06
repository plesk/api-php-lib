<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Database;

use PleskX\Api\Struct;

class UserInfo extends Struct
{
    public int $id;
    public string $login;
    public int $dbId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'login',
            'db-id',
        ]);
    }
}
