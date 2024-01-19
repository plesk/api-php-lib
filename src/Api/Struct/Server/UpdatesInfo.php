<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\AbstractStruct;

class UpdatesInfo extends AbstractStruct
{
    public string $lastInstalledUpdate;
    public bool $installUpdatesAutomatically;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'last_installed_update',
            'install_updates_automatically',
        ]);
    }
}
