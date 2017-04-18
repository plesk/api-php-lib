<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{
/**
     * @param array $properties
     * @return Struct\Info
     */
    public function create(array $properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('create');

        $info->addChild('site-id', $properties['site-id']);
        $info->addChild('name', $properties['name']);

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }
}
