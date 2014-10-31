<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Ip as Struct;

class Ip extends \PleskX\Api\Operator
{

    /**
     * @return Struct\Info[]
     */
    public function get()
    {
        $ips = [];
        $packet = $this->_client->getPacket();
        $packet->addChild('ip')->addChild('get');
        $response = $this->_client->request($packet);

        foreach ($response->addresses->ip_info as $ipInfo) {
            $ips[] = new Struct\Info($ipInfo);
        }

        return $ips;
    }

}
