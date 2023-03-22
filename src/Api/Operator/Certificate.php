<?php
// Copyright 1999-2022. Plesk International GmbH.

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

    public function install(array $properties, string $request, string $privateKey): bool
    {
        $packet = $this->client->getPacket();

        $installTag = $packet->addChild($this->wrapperTag)->addChild('install');
        foreach ($properties as $name => $value) {
            $installTag->{$name} = $value;
        }

        $contentTag = $installTag->addChild('content');
        $contentTag->addChild('csr', $request);
        $contentTag->addChild('pvt', $privateKey);

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
