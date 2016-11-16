<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class MailTest extends TestCase
{

    public function testCreate()
    {
        $webspace = $this->_createWebspace('example.dom');
        $mailname = $this->_client->mail()->create('test', $webspace->id, true, 'secret');

        $this->assertInternalType('integer', $mailname->id);
        $this->assertGreaterThan(0, $mailname->id);
        $this->assertEquals('test', $mailname->name);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace('example.dom');
        $mailname = $this->_client->mail()->create('test', $webspace->id);

        $result = $this->_client->mail()->delete('name', $mailname->name, $webspace->id);
        $this->assertTrue($result);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

}
