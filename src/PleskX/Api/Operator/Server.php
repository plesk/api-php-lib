<?php

class PleskX_Api_Operator_Server
{

    /** @var PleskX_Api_Client */
    private $_client;

    public function __construct($client)
    {
        $this->_client = $client;
    }

    /**
     * @return array
     */
    public function getProtos()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $response = $this->_client->request($packet);

        return (array)$response->server->get_protos->result->protos->proto;
    }

    public function getGeneralInfo()
    {
        return new PleskX_Api_Struct_Server_GeneralInfo($this->_getInfo('gen_info'));
    }

    public function getPreferences()
    {
        return new PleskX_Api_Struct_Server_Preferences($this->_getInfo('prefs'));
    }

    public function getAdmin()
    {
        return new PleskX_Api_Struct_Server_Admin($this->_getInfo('admin'));
    }

    /**
     * @param string $operation
     * @return mixed
     */
    private function _getInfo($operation)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get')->addChild($operation);
        $response = $this->_client->request($packet);

        return $response->server->get->result->$operation;
    }

}
