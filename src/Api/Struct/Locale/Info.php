<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Locale;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class Info extends Struct
{
    public string $id;
    public string $language;
    public string $country;

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            ['lang' => 'language'],
            'country',
        ]);
    }
}
