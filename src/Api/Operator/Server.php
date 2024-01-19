<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Server as Struct;
use PleskX\Api\XmlResponse;

class Server extends \PleskX\Api\Operator
{
    public function getProtos(): array
    {
        $packet = $this->client->getPacket();
        $packet->addChild($this->wrapperTag)->addChild('get_protos');
        $response = $this->client->request($packet);

        /** @psalm-suppress PossiblyNullPropertyFetch */
        return (array) $response->protos->proto;
    }

    public function getGeneralInfo(): Struct\GeneralInfo
    {
        return new Struct\GeneralInfo($this->getInfo('gen_info'));
    }

    public function getPreferences(): Struct\Preferences
    {
        return new Struct\Preferences($this->getInfo('prefs'));
    }

    public function getAdmin(): Struct\Admin
    {
        return new Struct\Admin($this->getInfo('admin'));
    }

    public function getKeyInfo(): array
    {
        $keyInfo = [];
        $keyInfoXml = $this->getInfo('key');

        foreach ($keyInfoXml->property ?? [] as $property) {
            $keyInfo[(string) $property->name] = (string) $property->value;
        }

        return $keyInfo;
    }

    public function getComponents(): array
    {
        $components = [];
        $componentsXml = $this->getInfo('components');

        foreach ($componentsXml->component ?? [] as $component) {
            $components[(string) $component->name] = (string) $component->version;
        }

        return $components;
    }

    public function getServiceStates(): array
    {
        $states = [];
        $statesXml = $this->getInfo('services_state');

        foreach ($statesXml->srv ?? [] as $service) {
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
        return new Struct\SessionPreferences($this->getInfo('session_setup'));
    }

    public function getShells(): array
    {
        $shells = [];
        $shellsXml = $this->getInfo('shells');

        foreach ($shellsXml->shell ?? [] as $shell) {
            $shells[(string) $shell->name] = (string) $shell->path;
        }

        return $shells;
    }

    public function getNetworkInterfaces(): array
    {
        $interfacesXml = $this->getInfo('interfaces');

        return (array) $interfacesXml->interface;
    }

    public function getStatistics(): Struct\Statistics
    {
        return new Struct\Statistics($this->getInfo('stat'));
    }

    public function getSiteIsolationConfig(): array
    {
        $config = [];
        $configXml = $this->getInfo('site-isolation-config');

        foreach ($configXml->property ?? [] as $property) {
            $config[(string) $property->name] = (string) $property->value;
        }

        return $config;
    }

    public function getUpdatesInfo(): Struct\UpdatesInfo
    {
        return new Struct\UpdatesInfo($this->getInfo('updates'));
    }

    public function createSession(string $login, string $clientIp): string
    {
        $packet = $this->client->getPacket();
        $sessionNode = $packet->addChild($this->wrapperTag)->addChild('create_session');
        $sessionNode->addChild('login', $login);
        $dataNode = $sessionNode->addChild('data');
        $dataNode->addChild('user_ip', base64_encode($clientIp));
        $dataNode->addChild('source_server');
        $response = $this->client->request($packet);

        return (string) $response->id;
    }

    private function getInfo(string $operation): XmlResponse
    {
        $packet = $this->client->getPacket();
        $packet->addChild($this->wrapperTag)->addChild('get')->addChild($operation);
        $response = $this->client->request($packet);

        return $response->$operation;
    }
}
