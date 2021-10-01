<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class UserInfo extends Struct
{
    public int $id;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
        ]);
    }
}
