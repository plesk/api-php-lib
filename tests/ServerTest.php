<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class ServerTest extends TestCase
{

    public function testGetProtos()
    {
        $protos = static::$_client->server()->getProtos();
        $this->assertInternalType('array', $protos);
        $this->assertContains('1.6.3.0', $protos);
    }

    public function testGetGenInfo()
    {
        $generalInfo = static::$_client->server()->getGeneralInfo();
        $this->assertGreaterThan(0, strlen($generalInfo->serverName));
        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $generalInfo->serverGuid);
        $this->assertEquals('standard', $generalInfo->mode);
    }

    public function testGetPreferences()
    {
        $preferences = static::$_client->server()->getPreferences();
        $this->assertInternalType('integer', $preferences->statTtl);
        $this->assertGreaterThan(0, $preferences->statTtl);
        $this->assertEquals(0, $preferences->restartApacheInterval);
    }

    public function testGetAdmin()
    {
        $admin = static::$_client->server()->getAdmin();
        $this->assertGreaterThan(0, strlen($admin->name));
        $this->assertContains('@', $admin->email);
    }

    public function testGetKeyInfo()
    {
        $keyInfo = static::$_client->server()->getKeyInfo();
        $this->assertInternalType('array', $keyInfo);
        $this->assertGreaterThan(0, count($keyInfo));
        $this->assertArrayHasKey('plesk_key_id', $keyInfo);
        $this->assertArrayHasKey('lim_date', $keyInfo);
    }

    public function testGetComponents()
    {
        $components = static::$_client->server()->getComponents();
        $this->assertInternalType('array', $components);
        $this->assertGreaterThan(0, count($components));
        $this->assertArrayHasKey('psa', $components);
        $this->assertArrayHasKey('php', $components);
    }

    public function testGetServiceStates()
    {
        $serviceStates = static::$_client->server()->getServiceStates();
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
        $preferences = static::$_client->server()->getSessionPreferences();
        $this->assertInternalType('integer', $preferences->loginTimeout);
        $this->assertGreaterThan(0, $preferences->loginTimeout);
    }

    public function testGetShells()
    {
        $shells = static::$_client->server()->getShells();
        $this->assertInternalType('array', $shells);
        $this->assertGreaterThan(0, count($shells));
        $this->assertArrayHasKey('/bin/bash', $shells);

        $bash = $shells['/bin/bash'];
        $this->assertEquals('/bin/bash', $bash);
    }

    public function testGetNetworkInterfaces()
    {
        $netInterfaces = static::$_client->server()->getNetworkInterfaces();
        $this->assertInternalType('array', $netInterfaces);
        $this->assertGreaterThan(0, count($netInterfaces));
    }

    public function testGetStatistics()
    {
        $stats = static::$_client->server()->getStatistics();
        $this->assertInternalType('integer', $stats->objects->clients);
        $this->assertEquals('psa', $stats->version->internalName);
    }

    public function testGetSiteIsolationConfig()
    {
        $config = static::$_client->server()->getSiteIsolationConfig();
        $this->assertInternalType('array', $config);
        $this->assertGreaterThan(0, count($config));
        $this->assertArrayHasKey('php', $config);
    }

    public function testGetUpdatesInfo()
    {
        $updatesInfo = static::$_client->server()->getUpdatesInfo();
        $this->assertInternalType('boolean', $updatesInfo->installUpdatesAutomatically);
    }

}
