<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Version extends \PleskX\Api\Struct
{
    /** @var string */
    public $internalName;

    /** @var string */
    public $version;

    /** @var string */
    public $build;

    /** @var string */
    public $osName;

    /** @var string */
    public $osVersion;

    /** @var string */
    public $osRelease;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['plesk_name' => 'internalName'],
            ['plesk_version' => 'version'],
            ['plesk_build' => 'build'],
            ['plesk_os' => 'osName'],
            ['plesk_os_version' => 'osVersion'],
            ['os_release' => 'osRelease'],
        ]);
    }
}
