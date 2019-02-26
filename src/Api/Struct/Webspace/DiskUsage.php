<?php
// Copyright 1999-2019. Plesk International GmbH.
// Author: Frederic Leclercq

namespace PleskX\Api\Struct\Webspace;

class DiskUsage extends \PleskX\Api\Struct
{
    /** @var integer */
    public $httpdocs;

    /** @var integer */
    public $httpsdocs;

    /** @var integer */
    public $subdomains;

    /** @var integer */
    public $anonftp;

    /** @var integer */
    public $logs;

    /** @var integer */
    public $dbases;

    /** @var integer */
    public $mailboxes;

    /** @var integer */
    public $maillists;

    /** @var integer */
    public $domaindumps;

    /** @var integer */
    public $configs;

    /** @var integer */
    public $chroot;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'httpdocs',
            'httpsdocs',
            'subdomains',
            'anonftp',
            'logs',
            'dbases',
            'mailboxes',
            'maillists',
            'domaindumps',
            'configs',
            'chroot',
        ]);
    }
}
