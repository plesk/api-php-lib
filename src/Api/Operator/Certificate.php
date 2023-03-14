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
    
    public function install(array $properties, $csr, $pvt): Struct\Info
    {
        $packet = $this->client->getPacket();

        $install = $packet->addChild($this->wrapperTag)->addChild('install');
        foreach ($properties as $name => $value) {
            $install->{$name} = $value;
        }

        $content = $install->addChild('content');
        $content->addChild('csr', $csr);
        $content->addChild('pvt', $pvt);

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }
    
}
