<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\EventLog;

class Event extends \PleskX\Api\Struct
{
    /** @var string */
    public $type;

    /** @var integer */
    public $time;

    /** @var string */
    public $class;

    /** @var string */
    public $id;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'type',
            'time',
            'class',
            'id',
        ]);
    }
}