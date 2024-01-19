<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Database;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public string $name;
    public string $type;
    public int $webspaceId;
    public int $dbServerId;
    public int $defaultUserId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'type',
            'webspace-id',
            'db-server-id',
            'default-user-id',
        ]);
    }
}
