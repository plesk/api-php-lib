<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Customer as Struct;

class Customer extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild('customer')->addChild('add')->addChild('gen_info');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $items = $this->_get($field, $value);
        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_get();
    }

    /**
     * @param string|null $field
     * @param integer|string|null $value
     * @return Struct\GeneralInfo|Struct\GeneralInfo[]
     */
    private function _get($field = null, $value = null)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('customer')->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $getTag->addChild('dataset')->addChild('gen_info');

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $customers = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $customers[] = new Struct\GeneralInfo($xmlResult->data->gen_info);
        }

        return $customers;
    }

}
