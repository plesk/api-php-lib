<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\AbstractStruct;

class Admin extends AbstractStruct
{
    public string $companyName;
    public string $name;
    public string $email;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['admin_cname' => 'companyName'],
            ['admin_pname' => 'name'],
            ['admin_email' => 'email'],
        ]);
    }
}
