<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\SecretKey as Struct;

class SecretKey extends \PleskX\Api\Operator
{

    protected $_wrapperTag = 'secret_key';

    /**
     * @param string $ipAddress
     * @return string
     */
    public function create($ipAddress)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('secret_key')->addChild('create')->addChild('ip_address', $ipAddress);
        $response = $this->_client->request($packet);
        return (string)$response->key;
    }

    /**
     * @param string $keyId
     * @return Struct\KeyInfo
     */
    public function getInfo($keyId)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('secret_key')->addChild('get_info')->addChild('filter')->addChild('key', $keyId);
        $response = $this->_client->request($packet);
        return new Struct\KeyInfo($response->key_info);
    }

    /**
     * @param string $keyId
     * @return bool
     */
    public function delete($keyId)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('secret_key')->addChild('delete')->addChild('filter')->addChild('key', $keyId);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

}
