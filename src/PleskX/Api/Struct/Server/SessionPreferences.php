<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Server;

class SessionPreferences extends \PleskX\Api\Struct
{
    /** @var integer */
    public $loginTimeout;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'login_timeout',
        ]);
    }
}