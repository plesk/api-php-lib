<?php

class PleskX_Api_Struct_Server_Admin extends PleskX_Api_Struct_Abstract
{
    /** @var string */
    public $companyName;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['admin_cname' => 'companyName'],
            ['admin_pname' => 'name'],
            ['admin_email' => 'email'],
        ]);
    }
}