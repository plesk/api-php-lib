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

    /**
     * @return PleskX_Api_Struct_Server_GeneralInfo
     */
    public function getGeneralInfo()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get')->addChild('gen_info');
        $response = $this->_client->request($packet);

        return new PleskX_Api_Struct_Server_GeneralInfo($response->server->get->result->gen_info);
    }

}
