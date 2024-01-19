<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class SessionTest extends AbstractTestCase
{
    public function testCreate()
    {
        $sessionToken = static::$client->session()->create('admin', '127.0.0.1');

        $this->assertIsString($sessionToken);
        $this->assertGreaterThan(10, strlen($sessionToken));
    }

    public function testGet()
    {
        $sessionId = static::$client->server()->createSession('admin', '127.0.0.1');
        $sessions = static::$client->session()->get();
        $this->assertArrayHasKey($sessionId, $sessions);

        $sessionInfo = $sessions[$sessionId];
        $this->assertEquals('admin', $sessionInfo->login);
        $this->assertEquals('127.0.0.1', $sessionInfo->ipAddress);
        $this->assertEquals($sessionId, $sessionInfo->id);
    }

    public function testTerminate()
    {
        $sessionId = static::$client->server()->createSession('admin', '127.0.0.1');
        static::$client->session()->terminate($sessionId);
        $sessions = static::$client->session()->get();
        $this->assertArrayNotHasKey($sessionId, $sessions);
    }
}
