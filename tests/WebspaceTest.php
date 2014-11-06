<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class WebspaceTest extends TestCase
{

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

}
