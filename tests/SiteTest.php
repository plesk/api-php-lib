<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class SiteTest extends TestCase
{

    /**
     * @param string $name
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createWebspace($name)
    {
        return $this->_client->webspace()->create([
            'name' => $name,
            'ip_address' => $this->_getIpAddress(),
        ], [
            'ftp_login' => 'test-login',
            'ftp_password' => 'test-password',
        ]);
    }

    private function _createSite($name, $webspace)
    {
        return $this->_client->site()->create([
            'name' => $name,
            'webspace-id' => $webspace->id,
        ]);
    }

    public function testCreate()
    {
        $webspace = $this->_createWebspace('example.dom');
        $site = $this->_createSite('addon.dom', $webspace);

        $this->assertInternalType('integer', $site->id);
        $this->assertGreaterThan(0, $site->id);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace('example.dom');
        $site = $this->_createSite('addon.dom', $webspace);

        $result = $this->_client->site()->delete('id', $site->id);
        $this->assertTrue($result);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = $this->_createWebspace('example.dom');
        $site = $this->_createSite('addon.dom', $webspace);

        $siteInfo = $this->_client->site()->get('id', $site->id);
        $this->assertEquals('addon.dom', $siteInfo->name);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testGetAll()
    {
        $webspace = $this->_createWebspace('example.dom');
        $this->_createSite('addon.dom', $webspace);
        $this->_createSite('addon2.dom', $webspace);

        $sitesInfo = $this->_client->site()->getAll();
        $this->assertCount(2, $sitesInfo);
        $this->assertEquals('addon.dom', $sitesInfo[0]->name);
        $this->assertEquals('addon.dom', $sitesInfo[0]->asciiName);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

}
