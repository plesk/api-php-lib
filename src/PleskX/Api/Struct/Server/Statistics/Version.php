<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Struct\Server\Statistics;

class Version extends \PleskX\Api\Struct
{

    /** @var string */
    public $internalName;

    /** @var string */
    public $version;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['plesk_name' => 'internalName'],
            ['plesk_version' => 'version'],
        ]);
    }
}