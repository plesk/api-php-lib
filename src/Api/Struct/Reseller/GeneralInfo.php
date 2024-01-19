<?php
// Copyright 1999-2024. WebPros International GmbH.

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
        if (!is_null($apiResponse->{'gen-info'})) {
            $this->initScalarProperties($apiResponse->{'gen-info'}, [
                ['pname' => 'personalName'],
                'login',
            ]);
        }

        $this->permissions = [];
        foreach ($apiResponse->permissions->permission ?? [] as $permissionInfo) {
            $this->permissions[(string) $permissionInfo->name] = (string) $permissionInfo->value;
        }
    }
}
