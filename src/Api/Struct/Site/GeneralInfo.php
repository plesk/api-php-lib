<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Site;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $creationDate;

    /** @var string */
    public $name;

    /** @var string */
    public $asciiName;

    /** @var string */
    public $guid;

    /** @var string */
    public $status;

    /** @var int */
    public $realSize;

    /** @var array */
    public $ipAddresses = [];

    /** @var string */
    public $description;

    /** @var string */
    public $webspaceGuid;

    /** @var int */
    public $webspaceId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cr_date' => 'creationDate'],
            'name',
            'ascii-name',
            'status',
            'real_size',
            'guid',
            'description',
            'webspace-guid',
            'webspace-id',
        ]);

        foreach ($apiResponse->dns_ip_address as $ip) {
            $this->ipAddresses[] = (string) $ip;
        }
    }
}
