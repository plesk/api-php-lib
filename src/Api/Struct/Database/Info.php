<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Database;

class Info extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var integer */
    public $webspaceId;

    /** @var integer */
    public $dbServerId;

    /** @var integer */
    public $defaultUserId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'type',
            'webspace-id',
            'db-server-id',
            'default-user-id',
        ]);
    }
}
