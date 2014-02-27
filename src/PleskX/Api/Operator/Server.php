<?php

class PleskX_Api_Operator_Server
{

    private $_client;

    public function __construct($client)
    {
        $this->_client = $client;
    }

    public function getProtos()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $response = $this->_client->request($packet);

        return (array)$response->server->get_protos->result->protos->proto;
    }

}
