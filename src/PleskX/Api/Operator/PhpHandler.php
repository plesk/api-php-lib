<?php
// Copyright 1999-2018. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\PhpHandler\Info;

class PhpHandler extends Operator
{
    /**
     * @param string $field
     * @param integer|string $value
     * @return Info
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');
        $filterTag = $getTag->addChild('filter');

        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);
        $xmlResult = $response->xpath('//result')[0];

        return new Info($xmlResult);
    }
}
