<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

class DatabaseServerTest extends TestCase
{

    public function testGetSupportedTypes()
    {
        $types = $this->_client->databaseServer()->getSupportedTypes();
        $this->assertGreaterThan(0, count($types));
        $this->assertContains('mysql', $types);
    }

}
