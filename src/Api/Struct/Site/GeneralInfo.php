<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Site;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public int $id;
    public string $creationDate;
    public string $name;
    public string $asciiName;
    public string $guid;
    public string $status;
    public int $realSize;
    public array $ipAddresses = [];
    public string $description;
    public string $webspaceGuid;
    public int $webspaceId;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
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
