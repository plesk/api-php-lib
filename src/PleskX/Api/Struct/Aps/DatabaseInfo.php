<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Aps;

class DatabaseInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $login;

    /** @var string */
    public $password;

    /** @var string */
    public $server;

    /** @var string */
    public $prefix;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'login',
            'password',
            'server',
            'prefix'
        ]);
    }
}