<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Dns;

class Info extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var integer */
    public $siteId;

    /** @var integer */
    public $siteAliasId;

    /** @var string */
    public $type;

    /** @var string */
    public $host;

    /** @var string */
    public $value;

    /** @var string */
    public $opt;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'site-id',
            'site-alias-id',
            'type',
            'host',
            'value',
            'opt',
        ]);
    }
}
