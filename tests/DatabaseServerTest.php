<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class DatabaseServerTest extends TestCase
{

    public function testGetSupportedTypes()
    {
        $types = $this->_client->databaseServer()->getSupportedTypes();
        $this->assertGreaterThan(0, count($types));
        $this->assertContains('mysql', $types);
    }

    public function testGet()
    {
        $dbServer = $this->_client->databaseServer()->get('id', 1);
        $this->assertEquals('localhost', $dbServer->host);
        $this->assertGreaterThan(0, $dbServer->port);
    }

    public function testGetAll()
    {
        $dbServers = $this->_client->databaseServer()->getAll();
        $this->assertInternalType('array', $dbServers);
        $this->assertGreaterThan(0, count($dbServers));
        $this->assertEquals('localhost', $dbServers[0]->host);
    }

}
