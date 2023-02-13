<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskXTest;

class DatabaseServerTest extends AbstractTestCase
{
    public function testGetSupportedTypes()
    {
        $types = static::$client->databaseServer()->getSupportedTypes();
        $this->assertGreaterThan(0, count($types));
        $this->assertContains('mysql', $types);
    }

    public function testGet()
    {
        $dbServer = static::$client->databaseServer()->get('id', 1);
        $this->assertEquals('localhost', $dbServer->host);
        $this->assertGreaterThan(0, $dbServer->port);
    }

    public function testGetAll()
    {
        $dbServers = static::$client->databaseServer()->getAll();
        $this->assertIsArray($dbServers);
        $this->assertGreaterThan(0, count($dbServers));
        $this->assertEquals('localhost', $dbServers[0]->host);
    }
}
