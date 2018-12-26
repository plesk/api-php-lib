<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Dns as Struct;

class Dns extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('add_rec');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

        return new Struct\Info($this->_client->request($packet));
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info
     */
    public function get($field, $value)
    {
        $items = $this->getAll($field, $value);
        return reset($items);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info[]
     */
    public function getAll($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get_rec');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $item = new Struct\Info($xmlResult->data);
            $item->id = (int)$xmlResult->id;
            $items[] = $item;
        }
        return $items;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value, 'del_rec');
    }
}
