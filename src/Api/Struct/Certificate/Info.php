<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Certificate;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public string $request;
    public string $privateKey;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['csr' => 'request'],
            ['pvt' => 'privateKey'],
        ]);
    }
}
