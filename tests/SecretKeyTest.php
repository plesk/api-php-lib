<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class SecretKeyTest extends TestCase
{

    public function testCreate()
    {
        $keyId = $this->_client->secretKey()->create('192.168.0.1');
        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $keyId);
        $this->_client->secretKey()->delete($keyId);
    }

    public function testGet()
    {
        $keyId = $this->_client->secretKey()->create('192.168.0.1');
        $keyInfo = $this->_client->secretKey()->get($keyId);

        $this->assertEquals($keyId, $keyInfo->key);
        $this->assertEquals('192.168.0.1', $keyInfo->ipAddress);
        $this->assertEquals('admin', $keyInfo->login);

        $this->_client->secretKey()->delete($keyId);
    }

    public function testGetAll()
    {
        $keyIds = [];
        $keyIds[] = $this->_client->secretKey()->create('192.168.0.1');
        $keyIds[] = $this->_client->secretKey()->create('192.168.0.2');

        $keys = $this->_client->secretKey()->getAll();
        $this->assertGreaterThanOrEqual(2, count($keys));
        $this->assertEquals('192.168.0.1', $keys[0]->ipAddress);

        foreach ($keyIds as $keyId) {
            $this->_client->secretKey()->delete($keyId);
        }
    }

    public function testDelete()
    {
        $keyId = $this->_client->secretKey()->create('192.168.0.1');
        $this->_client->secretKey()->delete($keyId);

        try {
            $this->_client->secretKey()->get($keyId);
            $this->fail("Secret key $keyId was not deleted.");
        } catch (Exception $exception) {
            $this->assertEquals(1013, $exception->getCode());
        }
    }

}
