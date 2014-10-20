<?php

class ServerTest extends TestCase
{

    public function testGetProtos()
    {
        $protos = $this->_client->server()->getProtos();
        $this->assertInternalType('array', $protos);
        $this->assertContains('1.6.3.0', $protos);
    }

    public function testGetGenInfo()
    {
        $generalInfo = $this->_client->server()->getGeneralInfo();
        $this->assertGreaterThan(0, strlen($generalInfo->serverName));
        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $generalInfo->serverGuid);
        $this->assertEquals('standard', $generalInfo->mode);
    }

    public function testGetPreferences()
    {
        $preferences = $this->_client->server()->getPreferences();
        $this->assertInternalType('integer', $preferences->statTtl);
        $this->assertGreaterThan(0, $preferences->statTtl);
        $this->assertEquals(0, $preferences->restartApacheInterval);
    }

    public function testGetAdmin()
    {
        $admin = $this->_client->server()->getAdmin();
        $this->assertGreaterThan(0, strlen($admin->companyName));
        $this->assertGreaterThan(0, strlen($admin->name));
        $this->assertContains('@', $admin->email);
    }

    public function testGetKeyInfo()
    {
        $keyInfo = $this->_client->server()->getKeyInfo();
        $this->assertInternalType('array', $keyInfo);
        $this->assertGreaterThan(0, count($keyInfo));
        $this->assertArrayHasKey('plesk_key_id', $keyInfo);
        $this->assertArrayHasKey('product_version', $keyInfo);
    }

    public function testGetComponents()
    {
        $components = $this->_client->server()->getComponents();
        $this->assertInternalType('array', $components);
        $this->assertGreaterThan(0, count($components));
        $this->assertArrayHasKey('psa', $components);
        $this->assertArrayHasKey('php', $components);
    }

    public function testGetServiceStates()
    {
        $serviceStates = $this->_client->server()->getServiceStates();
        $this->assertInternalType('array', $serviceStates);
        $this->assertGreaterThan(0, count($serviceStates));
        $this->assertArrayHasKey('web', $serviceStates);

        $webService = $serviceStates['web'];
        $this->assertInternalType('array', $webService);
        $this->assertArrayHasKey('id', $webService);
        $this->assertArrayHasKey('title', $webService);
        $this->assertArrayHasKey('state', $webService);
        $this->assertEquals('running', $webService['state']);
    }

    public function testGetSessionPreferences()
    {
        $preferences = $this->_client->server()->getSessionPreferences();
        $this->assertInternalType('integer', $preferences->loginTimeout);
        $this->assertGreaterThan(0, $preferences->loginTimeout);
    }

    public function testGetShells()
    {
        $shells = $this->_client->server()->getShells();
        $this->assertInternalType('array', $shells);
        $this->assertGreaterThan(0, count($shells));
        $this->assertArrayHasKey('/bin/bash', $shells);

        $bash = $shells['/bin/bash'];
        $this->assertEquals('/bin/bash', $bash);
    }

    public function testGetNetworkInterfaces()
    {
        $netInterfaces = $this->_client->server()->getNetworkInterfaces();
        $this->assertInternalType('array', $netInterfaces);
        $this->assertGreaterThan(0, count($netInterfaces));
    }

    public function testGetStatistics()
    {
        $stats = $this->_client->server()->getStatistics();
        $this->assertInternalType('integer', $stats->objects->clients);
        $this->assertEquals('psa', $stats->version->internalName);
    }

    public function testGetSiteIsolationConfig()
    {
        $config = $this->_client->server()->getSiteIsolationConfig();
        $this->assertInternalType('array', $config);
        $this->assertGreaterThan(0, count($config));
        $this->assertArrayHasKey('php', $config);
    }

}
