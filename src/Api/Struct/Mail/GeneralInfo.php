<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Mail;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'description',
        ]);
    }
}
