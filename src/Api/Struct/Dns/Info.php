<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Dns;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public int $id;
    public int $siteId;
    public int $siteAliasId;
    public string $type;
    public string $host;
    public string $value;
    public string $opt;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'site-id',
            'site-alias-id',
            'type',
            'host',
            'value',
            'opt',
        ]);
    }
}
