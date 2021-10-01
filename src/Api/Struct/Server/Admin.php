<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Admin extends Struct
{
    public string $companyName;
    public string $name;
    public string $email;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['admin_cname' => 'companyName'],
            ['admin_pname' => 'name'],
            ['admin_email' => 'email'],
        ]);
    }
}
