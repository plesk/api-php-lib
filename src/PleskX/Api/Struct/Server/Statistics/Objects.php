<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Objects extends \PleskX\Api\Struct
{

    /** @var integer */
    public $clients;

    /** @var integer */
    public $domains;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'clients',
            'domains',
        ]);
    }
}