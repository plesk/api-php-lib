<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{
    public function generate(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }
}
