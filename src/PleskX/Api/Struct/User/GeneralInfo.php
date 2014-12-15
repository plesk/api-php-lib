<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\User;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var string */
    public $login;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $ownerGuid;

    /** @var string */
    public $guid;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'login',
            'name',
            'email',
            'owner-guid',
            'guid',
        ]);
    }
}