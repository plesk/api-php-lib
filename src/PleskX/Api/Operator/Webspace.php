<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends \PleskX\Api\Operator
{

    public function getPermissionDescriptor()
    {
        $response = $this->request('get-permission-descriptor.filter');
        return new Struct\PermissionDescriptor($response);
    }

    public function getLimitDescriptor()
    {
        $response = $this->request('get-limit-descriptor.filter');
        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor()
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');
        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild('webspace')->addChild('add')->addChild('gen_setup');

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
        $packet = $this->_client->getPacket();
        $packet->addChild('webspace')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->_client->request($packet);
        return new Struct\GeneralInfo($response->data->gen_info);
    }

}
