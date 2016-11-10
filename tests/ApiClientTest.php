<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class ApiClientTest extends TestCase
{

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1005
     */
    public function testWrongProtocol()
    {
        $packet = $this->_client->getPacket('100.0.0');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testUnknownOperator()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('unknown');
        $this->_client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testInvalidXmlRequest()
    {
        $this->_client->request('<packet><wrongly formatted xml</packet>');
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1001
     */
    public function testInvalidCredentials()
    {
        $host = $this->_client->getHost();
        $port = $this->_client->getPort();
        $protocol = $this->_client->getProtocol();
        $client = new PleskX\Api\Client($host, $port, $protocol);
        $client->setCredentials('bad-login', 'bad-password');
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 11003
     */
    public function testInvalidSecretKey()
    {
        $host = $this->_client->getHost();
        $port = $this->_client->getPort();
        $protocol = $this->_client->getProtocol();
        $client = new PleskX\Api\Client($host, $port, $protocol);
        $client->setSecretKey('bad-key');
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    public function testLatestMajorProtocol()
    {
        $packet = $this->_client->getPacket('1.6');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    public function testLatestMinorProtocol()
    {
        $packet = $this->_client->getPacket('1.6.5');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    public function testRequestShortSyntax()
    {
        $response = $this->_client->request('server.get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorPlainRequest()
    {
        $response = $this->_client->server()->request('get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
        $this->assertEquals(36, strlen($response->getValue('server_guid')));
    }

    public function testRequestArraySyntax()
    {
        $response = $this->_client->request([
            'server' => [
                'get' => [
                    'gen_info' => '',
                ],
            ],
        ]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorArraySyntax()
    {
        $response = $this->_client->server()->request(['get' => ['gen_info' => '']]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testMultiRequest()
    {
        $responses = $this->_client->multiRequest([
            'server.get_protos',
            'server.get.gen_info',
        ]);

        $this->assertCount(2, $responses);

        $protos = (array)$responses[0]->protos->proto;
        $generalInfo = $responses[1];

        $this->assertContains('1.6.6.0', $protos);
        $this->assertGreaterThan(0, strlen($generalInfo->gen_info->server_name));
    }

    /**
     * @expectedException \PleskX\Api\Client\Exception
     */
    public function testConnectionError()
    {
        $client = new \PleskX\Api\Client('invalid-host.dom');
        $client->server()->getProtos();
    }

    public function testGetHost()
    {
        $client = new \PleskX\Api\Client('example.dom');
        $this->assertEquals('example.dom', $client->getHost());
    }

    public function testGetPort()
    {
        $client = new \PleskX\Api\Client('example.dom', 12345);
        $this->assertEquals(12345, $client->getPort());
    }

    public function testGetProtocol()
    {
        $client = new \PleskX\Api\Client('example.dom', 8880, 'http');
        $this->assertEquals('http', $client->getProtocol());
    }

}
