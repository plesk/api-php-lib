<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class IpTest extends TestCase
{

    public function testGet()
    {
        $ips = $this->_client->ip()->get();
        $this->assertGreaterThan(0, count($ips));

        $ip = reset($ips);
        $this->assertRegExp('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip->ipAddress);
    }

}
