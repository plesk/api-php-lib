<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public int $id;
    public string $creationDate;
    public string $name;
    public string $asciiName;
    public string $status;
    public int $realSize;
    public int $ownerId;
    public array $ipAddresses = [];
    public string $guid;
    public string $vendorGuid;
    public string $description;
    public string $adminDescription;
    public bool $enabled;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['cr_date' => 'creationDate'],
            'name',
            'ascii-name',
            'status',
            'real_size',
            'owner-id',
            'guid',
            'vendor-guid',
            'description',
            'admin-description',
        ]);

        foreach ($apiResponse->dns_ip_address as $ip) {
            $this->ipAddresses[] = (string) $ip;
        }

        $this->enabled = '0' === (string) $apiResponse->status;
    }
}
