<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class IpTest extends AbstractTestCase
{
    public function testGet()
    {
        $ips = static::$client->ip()->get();
        $this->assertGreaterThan(0, count($ips));

        $ip = reset($ips);
        $this->assertMatchesRegularExpression('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip->ipAddress);
    }
}
