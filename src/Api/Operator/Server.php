<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Server as Struct;
use PleskX\Api\XmlResponse;

class Server extends \PleskX\Api\Operator
{
    public function getProtos(): array
    {
        $packet = $this->_client->getPacket();
        $packet->addChild($this->_wrapperTag)->addChild('get_protos');
        $response = $this->_client->request($packet);

        return (array) $response->protos->proto;
    }

    public function getGeneralInfo(): Struct\GeneralInfo
    {
        return new Struct\GeneralInfo($this->_getInfo('gen_info'));
    }

    public function getPreferences(): Struct\Preferences
    {
        return new Struct\Preferences($this->_getInfo('prefs'));
    }

    public function getAdmin(): Struct\Admin
    {
        return new Struct\Admin($this->_getInfo('admin'));
    }

    public function getKeyInfo(): array
    {
        $keyInfo = [];
        $keyInfoXml = $this->_getInfo('key');

        foreach ($keyInfoXml->property as $property) {
            $keyInfo[(string) $property->name] = (string) $property->value;
        }

        return $keyInfo;
    }

    public function getComponents(): array
    {
        $components = [];
        $componentsXml = $this->_getInfo('components');

        foreach ($componentsXml->component as $component) {
            $components[(string) $component->name] = (string) $component->version;
        }

        return $components;
    }

    public function getServiceStates(): array
    {
        $states = [];
        $statesXml = $this->_getInfo('services_state');

        foreach ($statesXml->srv as $service) {
            $states[(string) $service->id] = [
                'id' => (string) $service->id,
                'title' => (string) $service->title,
                'state' => (string) $service->state,
            ];
        }

        return $states;
    }

    public function getSessionPreferences(): Struct\SessionPreferences
    {
        return new Struct\SessionPreferences($this->_getInfo('session_setup'));
    }

    public function getShells(): array
    {
        $shells = [];
        $shellsXml = $this->_getInfo('shells');

        foreach ($shellsXml->shell as $shell) {
            $shells[(string) $shell->name] = (string) $shell->path;
        }

        return $shells;
    }

    public function getNetworkInterfaces(): array
    {
        $interfacesXml = $this->_getInfo('interfaces');

        return (array) $interfacesXml->interface;
    }

    public function getStatistics(): Struct\Statistics
    {
        return new Struct\Statistics($this->_getInfo('stat'));
    }

    public function getSiteIsolationConfig(): array
    {
        $config = [];
        $configXml = $this->_getInfo('site-isolation-config');

        foreach ($configXml->property as $property) {
            $config[(string) $property->name] = (string) $property->value;
        }

        return $config;
    }

    public function getUpdatesInfo(): Struct\UpdatesInfo
    {
        return new Struct\UpdatesInfo($this->_getInfo('updates'));
    }

    /**
     * @param string $login
     * @param string $clientIp
     *
     * @return string
     */
    public function createSession(string $login, string $clientIp): string
    {
        $packet = $this->_client->getPacket();
        $sessionNode = $packet->addChild($this->_wrapperTag)->addChild('create_session');
        $sessionNode->addChild('login', $login);
        $dataNode = $sessionNode->addChild('data');
        $dataNode->addChild('user_ip', base64_encode($clientIp));
        $dataNode->addChild('source_server');
        $response = $this->_client->request($packet);

        return (string) $response->id;
    }

    private function _getInfo(string $operation): XmlResponse
    {
        $packet = $this->_client->getPacket();
        $packet->addChild($this->_wrapperTag)->addChild('get')->addChild($operation);
        $response = $this->_client->request($packet);

        return $response->$operation;
    }
}
