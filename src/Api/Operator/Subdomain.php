<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Subdomain as Struct;

class Subdomain extends \PleskX\Api\Operator
{
    public function create(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add');

        foreach ($properties as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $propertyName => $propertyValue) {
                    $property = $info->addChild($name);
                    /** @psalm-suppress UndefinedPropertyAssignment */
                    $property->name = $propertyName;
                    /** @psalm-suppress UndefinedPropertyAssignment */
                    $property->value = $propertyValue;
                }
                continue;
            }
            $info->{$name} = $value;
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete(string $field, $value): bool
    {
        return $this->deleteBy($field, $value);
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
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, (string) $value);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if (!$xmlResult || empty($xmlResult->data)) {
                continue;
            }
            $item = new Struct\Info($xmlResult->data);
            $item->id = (int) $xmlResult->id;
            $items[] = $item;
        }

        return $items;
    }
}
