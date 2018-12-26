<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Database;

class UserInfo extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $login;

    /** @var integer */
    public $dbId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'login',
            'db-id',
        ]);
    }
}
