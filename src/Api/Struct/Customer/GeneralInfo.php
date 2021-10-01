<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Customer;

use PleskX\Api\Struct;
use PleskX\Api\XmlResponse;

class GeneralInfo extends Struct
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

    public function __construct(XmlResponse $apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
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
    }
}
