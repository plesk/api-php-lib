<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Struct\Customer;

use PleskX\Api\AbstractStruct;

class GeneralInfo extends AbstractStruct
{
    public int $id;
    public string $company;
    public string $personalName;
    public string $login;
    public string $guid;
    public string $email;
    public string $phone;
    public string $fax;
    public string $address;
    public string $postalCode;
    public string $city;
    public string $state;
    public string $country;
    public string $description;
    public string $externalId;
    public bool $enabled;

    public function __construct(\SimpleXMLElement $apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['cname' => 'company'],
            ['pname' => 'personalName'],
            'login',
            'guid',
            'email',
            'phone',
            'fax',
            'address',
            ['pcode' => 'postalCode'],
            'city',
            'state',
            'country',
            'external-id',
            'description',
        ]);

        $this->enabled = '0' === (string) $apiResponse->status;
    }
}
