<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SecretKey as Struct;

class SecretKey extends \PleskX\Api\Operator
{
    protected string $wrapperTag = 'secret_key';

    /**
     * @param string $ipAddress
     * @param string $description
     *
     * @return string
     */
    public function create($ipAddress = '', $description = '')
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

    /**
     * @param string $keyId
     *
     * @return bool
     */
    public function delete($keyId)
    {
        return $this->deleteBy('key', $keyId, 'delete');
    }

    /**
     * @param string $keyId
     *
     * @return Struct\Info
     */
    public function get($keyId)
    {
        $items = $this->getBy($keyId);

        return reset($items);
    }

    /**
     * @return Struct\Info[]
     */
    public function getAll()
    {
        return $this->getBy();
    }

    /**
     * @param string|null $keyId
     *
     * @return Struct\Info[]
     */
    public function getBy($keyId = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get_info');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($keyId)) {
            $filterTag->addChild('key', $keyId);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result/key_info') as $keyInfo) {
            $items[] = new Struct\Info($keyInfo);
        }

        return $items;
    }
}
