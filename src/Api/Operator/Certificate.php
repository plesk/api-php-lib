<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{
    public function generate(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }

    /**
     * @param array $properties
     * @param string|Struct\Info $certificate
     * @param string|null $privateKey
     */
    public function install(array $properties, $certificate, ?string $privateKey = null): bool
    {
        return $this->callApi('install', $properties, $certificate, $privateKey);
    }

    /**
     * @param array $properties
     * @param Struct\Info $certificate
     */
    public function update(array $properties, Struct\Info $certificate): bool
    {
        return $this->callApi('update', $properties, $certificate);
    }

    /**
     * @param string $method
     * @param array $properties
     * @param string|Struct\Info $certificate
     * @param string|null $privateKey
     */
    private function callApi(string $method, array $properties, $certificate, ?string $privateKey = null): bool
    {
        $packet = $this->client->getPacket();

        $installTag = $packet->addChild($this->wrapperTag)->addChild($method);
        foreach ($properties as $name => $value) {
            $installTag->{$name} = $value;
        }

        $contentTag = $installTag->addChild('content');
        if (is_string($certificate)) {
            $contentTag->addChild('csr', $certificate);
            $contentTag->addChild('pvt', $privateKey);
        } elseif ($certificate instanceof \PleskX\Api\Struct\Certificate\Info) {
            foreach ($certificate->getMapping() as $name => $value) {
                $contentTag->{$name} = $value;
            }
        }
        $result = $this->client->request($packet);

        return 'ok' == (string) $result->status;
    }

    public function delete(string $name, array $properties): bool
    {
        $packet = $this->client->getPacket();

        $removeTag = $packet->addChild($this->wrapperTag)->addChild('remove');
        $removeTag->addChild('filter')->addChild('name', $name);

        foreach ($properties as $name => $value) {
            $removeTag->{$name} = $value;
        }

        $result = $this->client->request($packet);

        return 'ok' == (string) $result->status;
    }
}
