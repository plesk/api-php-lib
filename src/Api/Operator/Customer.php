<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Customer as Struct;

class Customer extends \PleskX\Api\Operator
{
    public function create(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add')->addChild('gen_info');

        foreach ($properties as $name => $value) {
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
     * @return Struct\GeneralInfo
     */
    public function get(string $field, $value): Struct\GeneralInfo
    {
        $items = $this->getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll(): array
    {
        return $this->getItems(Struct\GeneralInfo::class, 'gen_info');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function enable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 0]);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function disable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 1]);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param array $properties
     *
     * @return bool
     */
    public function setProperties(string $field, $value, array $properties): bool
    {
        $packet = $this->client->getPacket();
        $setTag = $packet->addChild($this->wrapperTag)->addChild('set');
        $setTag->addChild('filter')->addChild($field, (string) $value);
        $genInfoTag = $setTag->addChild('values')->addChild('gen_info');
        foreach ($properties as $property => $propertyValue) {
            $genInfoTag->addChild($property, (string) $propertyValue);
        }

        $response = $this->client->request($packet);

        return 'ok' === (string) $response->status;
    }
}
