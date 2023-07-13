<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SecretKey as Struct;

class SecretKey extends \PleskX\Api\Operator
{
    protected string $wrapperTag = 'secret_key';

    public function create(string $ipAddress = '', string $description = ''): string
    {
        $packet = $this->client->getPacket();
        $createTag = $packet->addChild($this->wrapperTag)->addChild('create');

        if ('' !== $ipAddress) {
            $createTag->addChild('ip_address', $ipAddress);
        }

        if ('' !== $description) {
            $createTag->addChild('description', $description);
        }

        $response = $this->client->request($packet);

        return (string) $response->key;
    }

    public function delete(string $keyId): bool
    {
        return $this->deleteBy('key', $keyId, 'delete');
    }

    public function get(string $keyId): Struct\Info
    {
        $items = $this->getBy($keyId);

        return reset($items);
    }

    /**
     * @return Struct\Info[]
     */
    public function getAll(): array
    {
        return $this->getBy();
    }

    /**
     * @param string|null $keyId
     *
     * @return Struct\Info[]
     */
    public function getBy($keyId = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get_info');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($keyId)) {
            $filterTag->addChild('key', $keyId);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ((array) $response->xpath('//result/key_info') as $keyInfo) {
            if (!$keyInfo) {
                continue;
            }
            $items[] = new Struct\Info($keyInfo);
        }

        return $items;
    }
}
