<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Mail;

class Info extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
        ]);
    }
}
