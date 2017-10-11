<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Aps;

class InstallInfo extends \PleskX\Api\Struct
{
    /** @var int */
    public $domain_id;

    /** @var string */
    public $domain_name;

    /** @var int */
    public $subdomain_id;

    /** @var string */
    public $subdomain_name;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['domain-id' => 'domain_id'],
            ['domain-name' => 'domain_name'],
            ['subdomain-id' => 'subdomain_id'],
            ['subdomain-name' => 'subdomain_name']
        ]);
    }
}
