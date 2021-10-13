<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Customer as Struct;

class Customer extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     *
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('add')->addChild('gen_info');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        $response = $this->_client->request($packet);

        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $items = $this->_getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_getItems(Struct\GeneralInfo::class, 'gen_info');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function enable(string $field, $value): bool
    {
        return $this->setProperty($field, $value, 'status', 0);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function disable(string $field, $value): bool
    {
        return $this->setProperty($field, $value, 'status', 1);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param string $property
     * @param int|string $propertyValue
     *
     * @return bool
     */
    public function setProperty(string $field, $value, string $property, $propertyValue): bool
    {
        $packet = $this->_client->getPacket();
        $setTag = $packet->addChild($this->_wrapperTag)->addChild('set');
        $setTag->addChild('filter')->addChild($field, (string) $value);
        $genInfoTag = $setTag->addChild('values')->addChild('gen_info');
        $genInfoTag->addChild($property, (string) $propertyValue);

        $response = $this->_client->request($packet);

        return 'ok' === (string) $response->status;
    }

}
