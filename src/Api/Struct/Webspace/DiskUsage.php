<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class DiskUsage extends AbstractStruct
{
    public int $httpdocs;
    public int $httpsdocs;
    public int $subdomains;
    public int $anonftp;
    public int $logs;
    public int $dbases;
    public int $mailboxes;
    public int $maillists;
    public int $domaindumps;
    public int $configs;
    public int $chroot;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
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
