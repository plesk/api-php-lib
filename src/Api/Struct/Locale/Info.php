<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Locale;

use PleskX\Api\Struct;

class Info extends Struct
{
    public string $id;
    public string $language;
    public string $country;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            ['lang' => 'language'],
            'country',
        ]);
    }
}
