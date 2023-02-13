<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Struct\Certificate;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $request;
    public string $privateKey;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['csr' => 'request'],
            ['pvt' => 'privateKey'],
        ]);
    }
}
