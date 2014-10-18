<?php

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Server as Struct;

class Server
{

    /** @var \PleskX\Api\Client */
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
        return new Struct\GeneralInfo($this->_getInfo('gen_info'));
    }

    public function getPreferences()
    {
        return new Struct\Preferences($this->_getInfo('prefs'));
    }

    public function getAdmin()
    {
        return new Struct\Admin($this->_getInfo('admin'));
    }

    /**
     * @param string $operation
     * @return \SimpleXMLElement
     */
    private function _getInfo($operation)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get')->addChild($operation);
        $response = $this->_client->request($packet);

        return $response->server->get->result->$operation;
    }

}
