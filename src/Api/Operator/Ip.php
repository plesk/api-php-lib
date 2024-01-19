<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ip as Struct;

class Ip extends \PleskX\Api\Operator
{
    /**
     * @return Struct\Info[]
     */
    public function get(): array
    {
        $ips = [];
        $packet = $this->client->getPacket();
        $packet->addChild($this->wrapperTag)->addChild('get');
        $response = $this->client->request($packet);

        foreach ($response->addresses->ip_info ?? [] as $ipInfo) {
            $ips[] = new Struct\Info($ipInfo);
        }

        return $ips;
    }
}
