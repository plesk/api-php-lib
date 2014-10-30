<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function generate($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild('certificate')->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

}
