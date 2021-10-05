<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     *
     * @return Struct\Info
     */
    public function generate($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        $response = $this->_client->request($packet);

        return new Struct\Info($response);
    }
}
