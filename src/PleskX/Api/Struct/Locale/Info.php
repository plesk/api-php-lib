<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Locale;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;

    /** @var string */
    public $language;

    /** @var string */
    public $country;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            ['lang' => 'language'],
            'country'
        ]);
    }
}