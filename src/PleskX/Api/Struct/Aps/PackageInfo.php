<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Aps;

class PackageInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $version;

    /** @var string */
    public $release;

    /** @var string */
    public $vendor;

    /** @var string */
    public $packager;

    /** @var string */
    public $isUploaded;

    /** @var string */
    public $isVisible;

    /** @var int */
    public $id;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'version',
            'release',
            'vendor',
            'packager',
            'is_uploaded' => 'isUploaded',
            'isVisible' => 'isVisible',
            'id'
        ]);
    }
}
