<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Certificate;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $request;

    /** @var string */
    public $privateKey;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['csr' => 'request'],
            ['pvt' => 'privateKey'],
        ]);
    }
}