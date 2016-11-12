<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Customer;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $company;

    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    /** @var string */
    public $guid;

    /** @var string */
    public $email;

    /** @var string */
    public $description;

    /** @var string */
    public $externalId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cname' => 'company'],
            ['pname' => 'personalName'],
            'login',
            'guid',
            'email',
            'external-id',
            'description',
        ]);
    }
}