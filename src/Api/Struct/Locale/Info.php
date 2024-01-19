<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Locale;

use PleskX\Api\AbstractStruct;

class Info extends AbstractStruct
{
    public string $id;
    public string $language;
    public string $country;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            ['lang' => 'language'],
            'country',
        ]);
    }
}
