<?php

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{

    public function generate($properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild('certificate')->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

        $response = $this->_client->request($packet);
        return new Struct\Info($response->certificate->generate->result);
    }

}
