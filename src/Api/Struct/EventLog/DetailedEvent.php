<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\EventLog;

class DetailedEvent extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $type;

    /** @var integer */
    public $time;

    /** @var string */
    public $class;

    /** @var string */
    public $objectId;

    /** @var string */
    public $user;

    /** @var string */
    public $host;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'type',
            'time',
            'class',
            ['obj_id' => 'objectId'],
            'user',
            'host',
        ]);
    }
}
