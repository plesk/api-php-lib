<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\PhpHandler;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $id;
    public string $displayName;
    public string $fullVersion;
    public string $version;
    public string $type;
    public string $path;
    public string $clipath;
    public string $phpini;
    public string $custom;
    public string $handlerStatus;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'display-name',
            'full-version',
            'version',
            'type',
            'path',
            'clipath',
            'phpini',
            'custom',
            'handler-status',
        ]);
    }
}
