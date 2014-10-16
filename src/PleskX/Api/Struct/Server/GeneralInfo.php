<?php

class PleskX_Api_Struct_Server_GeneralInfo extends PleskX_Api_Struct_Abstract
{
    /** @var string */
    public $serverName;

    /** @var string */
    public $serverGuid;

    /** @var string */
    public $mode;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'server_name',
            'server_guid',
            'mode',
        ]);
    }
}