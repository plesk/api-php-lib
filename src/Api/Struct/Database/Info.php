<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Database;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public string $name;
    public string $type;
    public int $webspaceId;
    public int $dbServerId;
    public int $defaultUserId;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'type',
            'webspace-id',
            'db-server-id',
            'default-user-id',
        ]);
    }
}
