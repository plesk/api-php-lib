<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

use PleskX\Api\AbstractStruct;

class Version extends AbstractStruct
{
    public string $internalName;
    public string $version;
    public string $build;
    public string $osName;
    public string $osVersion;
    public string $osRelease;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['plesk_name' => 'internalName'],
            ['plesk_version' => 'version'],
            ['plesk_build' => 'build'],
            ['plesk_os' => 'osName'],
            ['plesk_os_version' => 'osVersion'],
            ['os_release' => 'osRelease'],
        ]);
    }
}
