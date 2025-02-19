<?php
// Copyright 1999-2025. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\DatabaseServer as Struct;

class DatabaseServer extends \PleskX\Api\Operator
{
    protected string $wrapperTag = 'db_server';

    public function getSupportedTypes(): array
    {
        $response = $this->request('get-supported-types');

        return (array) $response->type;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info
     */
    public function get(string $field, $value): Struct\Info
    {
        $items = $this->getBy($field, $value);

        return reset($items);
    }

    /**
     * @return Struct\Info[]
     */
    public function getAll(): array
    {
        return $this->getBy();
    }

    public function getDefault(string $type): Struct\Info
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get-default');
        $filterTag = $getTag->addChild('filter');
        /** @psalm-suppress UndefinedPropertyAssignment */
        $filterTag->type = $type;

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }

    /**
     * @param string|null $field
     * @param int|string|null $value
     *
     * @return Struct\Info[]
     */
    private function getBy($field = null, $value = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = (string) $value;
        }

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
}
