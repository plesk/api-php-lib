<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class UpdatesInfo extends Struct
{
    public string $lastInstalledUpdate;
    public bool $installUpdatesAutomatically;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'last_installed_update',
            'install_updates_automatically',
        ]);
    }
}
