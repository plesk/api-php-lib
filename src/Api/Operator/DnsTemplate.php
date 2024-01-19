<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Dns as Struct;

class DnsTemplate extends \PleskX\Api\Operator
{
    protected string $wrapperTag = 'dns';

    /**
     * @param array $properties
     *
     * @return Struct\Info
     */
    public function create(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add_rec');

        unset($properties['site-id'], $properties['site-alias-id']);
        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        return new Struct\Info($this->client->request($packet));
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info
     */
    public function get(string $field, $value): Struct\Info
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info[]
     */
    public function getAll($field = null, $value = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get_rec');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = (string) $value;
        }
        $getTag->addChild('template');

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if (!$xmlResult) {
                continue;
            }
            if (!is_null($xmlResult->data)) {
                $item = new Struct\Info($xmlResult->data);
                $item->id = (int) $xmlResult->id;
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete(string $field, $value): bool
    {
        $packet = $this->client->getPacket();
        $delTag = $packet->addChild($this->wrapperTag)->addChild('del_rec');
        $delTag->addChild('filter')->addChild($field, (string) $value);
        $delTag->addChild('template');

        $response = $this->client->request($packet);

        return 'ok' === (string) $response->status;
    }
}
