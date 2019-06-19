<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\Struct;

class Info extends Struct
{
    /** @var integer */
    public $id;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
        ]);
    }
}
