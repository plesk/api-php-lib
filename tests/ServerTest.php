<?php

class ServerTest extends TestCase_Abstract
{

    public function testGetProtos()
    {
        $protos = $this->_client->server()->getProtos();
        $this->assertTrue(is_array($protos));
        $this->assertTrue(in_array('1.6.3.0', $protos));
    }

}
