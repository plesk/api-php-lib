<?php

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Server as Struct;

class Server extends \PleskX\Api\Operator
{

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
     * @return array
     */
    public function getKeyInfo()
    {
        $keyInfo = [];
        $keyInfoXml = $this->_getInfo('key');

        foreach ($keyInfoXml->property as $property) {
            $keyInfo[(string)$property->name] = (string)$property->value;
        }

        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getComponents()
    {
        $components = [];
        $componentsXml = $this->_getInfo('components');

        foreach ($componentsXml->component as $component) {
            $components[(string)$component->name] = (string)$component->version;
        }

        return $components;
    }

    /**
     * @return array
     */
    public function getServiceStates()
    {
        $states = [];
        $statesXml = $this->_getInfo('services_state');

        foreach ($statesXml->srv as $service) {
            $states[(string)$service->id] = [
                'id' => (string)$service->id,
                'title' => (string)$service->title,
                'state' => (string)$service->state,
            ];
        }

        return $states;
    }

    public function getSessionPreferences()
    {
        return new Struct\SessionPreferences($this->_getInfo('session_setup'));
    }

    /**
     * @return array
     */
    public function getShells()
    {
        $shells = [];
        $shellsXml = $this->_getInfo('shells');

        foreach ($shellsXml->shell as $shell) {
            $shells[(string)$shell->name] = (string)$shell->path;
        }

        return $shells;
    }

    /**
     * @return array
     */
    public function getNetworkInterfaces()
    {
        $interfacesXml = $this->_getInfo('interfaces');
        return (array)$interfacesXml->interface;
    }

    public function getStatistics()
    {
        return new Struct\Statistics($this->_getInfo('stat'));
    }

    /**
     * @return array
     */
    public function getSiteIsolationConfig()
    {
        $config = [];
        $configXml = $this->_getInfo('site-isolation-config');

        foreach ($configXml->property as $property) {
            $config[(string)$property->name] = (string)$property->value;
        }

        return $config;
    }

    public function getUpdatesInfo()
    {
        return new Struct\UpdatesInfo($this->_getInfo('updates'));
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
