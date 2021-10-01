<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Dns;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public int $id;
    public int $siteId;
    public int $siteAliasId;
    public string $type;
    public string $host;
    public string $value;
    public string $opt;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
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
