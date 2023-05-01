<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Reseller;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public int $id;
    public string $personalName;
    public string $login;
    public array $permissions;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse->{'gen-info'}, [
            ['pname' => 'personalName'],
            'login',
        ]);

        $this->permissions = [];
        foreach ($apiResponse->permissions->permission as $permissionInfo) {
            $this->permissions[(string) $permissionInfo->name] = (string) $permissionInfo->value;
        }
    }
}
