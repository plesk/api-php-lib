<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

class SessionTest extends TestCase
{

    public function testGet()
    {
        $sessionId = $this->_client->server()->createSession('admin', '127.0.0.1');
        $sessions = $this->_client->session()->get();
        $this->assertArrayHasKey($sessionId, $sessions);

        $sessionInfo = $sessions[$sessionId];
        $this->assertEquals('admin', $sessionInfo->login);
        $this->assertEquals('127.0.0.1', $sessionInfo->ipAddress);
        $this->assertEquals($sessionId, $sessionInfo->id);
    }

    public function testTerminate()
    {
        $sessionId = $this->_client->server()->createSession('admin', '127.0.0.1');
        $this->_client->session()->terminate($sessionId);
        $sessions = $this->_client->session()->get();
        $this->assertArrayNotHasKey($sessionId, $sessions);
    }

}
