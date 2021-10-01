<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Reseller;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class GeneralInfo extends Struct
{
    public int $id;
    public string $personalName;
    public string $login;
    public array $permissions;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse->{'gen-info'}, [
            ['pname' => 'personalName'],
            'login',
        ]);

        $this->permissions = [];
        foreach ($apiResponse->permissions->permission as $permissionInfo) {
            $this->permissions[(string) $permissionInfo->name] = (string) $permissionInfo->value;
        }
    }
}
