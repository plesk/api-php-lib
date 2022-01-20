<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\PhpHandler\Info;

class PhpHandler extends Operator
{
    /**
     * @param string|null $field
     * @param int|string|null $value
     *
     * @return Info
     */
    public function get($field = null, $value = null): Info
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');
        $filterTag = $getTag->addChild('filter');

        if (!is_null($field)) {
            $filterTag->addChild($field, (string) $value);
        }

        $response = $this->client->request($packet, Client::RESPONSE_FULL);
        $xmlResult = $response->xpath('//result')[0];

        return new Info($xmlResult);
    }

    /**
     * @param string|null $field
     * @param int|string $value
     *
     * @return Info[]
     */
    public function getAll($field = null, $value = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, (string) $value);
        }

        $response = $this->client->request($packet, Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $item = new Info($xmlResult);
            $items[] = $item;
        }

        return $items;
    }
}
