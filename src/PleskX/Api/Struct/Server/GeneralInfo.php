<?php
class PleskX_Api_Struct_Server_GeneralInfo
{
    /** @var string */
    public $serverName;

    /** @var string */
    public $serverGuid;

    /** @var string */
    public $mode;

    public function __construct($apiResponse)
    {
        $this->serverName = (string)$apiResponse->server_name;
        $this->serverGuid = (string)$apiResponse->server_guid;
        $this->mode = (string)$apiResponse->mode;
    }
}