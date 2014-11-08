<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class WebspaceTest extends TestCase
{

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createWebspace()
    {
        $ips = $this->_client->ip()->get();
        $ipInfo = reset($ips);
        return $this->_client->webspace()->create([
            'name' => 'example-test.dom',
            'ip_address' => $ipInfo->ipAddress,
        ]);
    }

    public function testGetPermissionDescriptor()
    {
        $descriptor = $this->_client->webspace()->getPermissionDescriptor();
        $this->assertInternalType('array', $descriptor->permissions);
        $this->assertGreaterThan(0, count($descriptor->permissions));
    }

    public function testGetLimitDescriptor()
    {
        $descriptor = $this->_client->webspace()->getLimitDescriptor();
        $this->assertInternalType('array', $descriptor->limits);
        $this->assertGreaterThan(0, count($descriptor->limits));
    }

    public function testGetPhysicalHostingDescriptor()
    {
        $descriptor = $this->_client->webspace()->getPhysicalHostingDescriptor();
        $this->assertInternalType('array', $descriptor->properties);
        $this->assertGreaterThan(0, count($descriptor->properties));

        $ftpLoginProperty = $descriptor->properties['ftp_login'];
        $this->assertEquals('ftp_login', $ftpLoginProperty->name);
        $this->assertEquals('string', $ftpLoginProperty->type);
    }

    public function testCreate()
    {
        $webspace = $this->_createWebspace();
        $this->assertInternalType('integer', $webspace->id);
        $this->assertGreaterThan(0, $webspace->id);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace();
        $result = $this->_client->webspace()->delete('id', $webspace->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $webspace = $this->_createWebspace();
        $webspaceInfo = $this->_client->webspace()->get('id', $webspace->id);
        $this->assertEquals('example-test.dom', $webspaceInfo->name);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

}
