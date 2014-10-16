<?php

class ServerTest extends TestCase_Abstract
{

    public function testGetProtos()
    {
        $protos = $this->_client->server()->getProtos();
        $this->assertTrue(is_array($protos));
        $this->assertTrue(in_array('1.6.3.0', $protos));
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

}
