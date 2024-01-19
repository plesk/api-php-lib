<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Dns as Struct;

class Dns extends \PleskX\Api\Operator
{
    public function create(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add_rec');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        return new Struct\Info($this->client->request($packet));
    }

    /**
     * Send multiply records by one request.
     *
     * @param array $records
     *
     * @return \SimpleXMLElement[]
     */
    public function bulkCreate(array $records): array
    {
        $packet = $this->client->getPacket();

        foreach ($records as $properties) {
            $info = $packet->addChild($this->wrapperTag)->addChild('add_rec');

            foreach ($properties as $name => $value) {
                $info->{$name} = $value;
            }
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if ($xmlResult) {
                $items[] = $xmlResult;
            }
        }

        return $items;
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
    public function getAll(string $field, $value): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get_rec');

        $filterTag = $getTag->addChild('filter');
        $filterTag->addChild($field, (string) $value);

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
        return $this->deleteBy($field, $value, 'del_rec');
    }

    /**
     * Delete multiply records by one request.
     *
     * @param array $recordIds
     *
     * @return \SimpleXMLElement[]
     */
    public function bulkDelete(array $recordIds): array
    {
        $packet = $this->client->getPacket();

        foreach ($recordIds as $recordId) {
            $packet->addChild($this->wrapperTag)->addChild('del_rec')
                ->addChild('filter')->addChild('id', $recordId);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if ($xmlResult) {
                $items[] = $xmlResult;
            }
        }

        return $items;
    }
}
