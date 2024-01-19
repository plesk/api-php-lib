<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskX\Api\Exception;

class SecretKeyTest extends AbstractTestCase
{
    public function testCreate()
    {
        $keyId = static::$client->secretKey()->create('192.168.0.1');
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $keyId
        );
        static::$client->secretKey()->delete($keyId);
    }

    public function testCreateAutoIp()
    {
        $keyId = static::$client->secretKey()->create();
        $this->assertNotEmpty($keyId);
        static::$client->secretKey()->delete($keyId);
    }

    public function testCreateMultiIps()
    {
        $keyId = static::$client->secretKey()->create(join(',', ['192.168.0.1', '192.168.0.2']));
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $keyId
        );
        static::$client->secretKey()->delete($keyId);
    }

    public function testCreateWithDescription()
    {
        $keyId = static::$client->secretKey()->create('192.168.0.1', 'test key');
        $keyInfo = static::$client->secretKey()->get($keyId);

        $this->assertEquals('test key', $keyInfo->description);

        static::$client->secretKey()->delete($keyId);
    }

    public function testGet()
    {
        $keyId = static::$client->secretKey()->create('192.168.0.1');
        $keyInfo = static::$client->secretKey()->get($keyId);

        $this->assertNotEmpty($keyInfo->key);
        $this->assertEquals('192.168.0.1', $keyInfo->ipAddress);
        $this->assertEquals('admin', $keyInfo->login);

        static::$client->secretKey()->delete($keyId);
    }

    public function testGetAll()
    {
        $keyIds = [];
        $keyIds[] = static::$client->secretKey()->create('192.168.0.1');
        $keyIds[] = static::$client->secretKey()->create('192.168.0.2');

        $keys = static::$client->secretKey()->getAll();
        $this->assertGreaterThanOrEqual(2, count($keys));

        $keyIpAddresses = array_map(function ($key) {
            return $key->ipAddress;
        }, $keys);
        $this->assertContains('192.168.0.1', $keyIpAddresses);
        $this->assertContains('192.168.0.2', $keyIpAddresses);

        foreach ($keyIds as $keyId) {
            static::$client->secretKey()->delete($keyId);
        }
    }

    public function testDelete()
    {
        $keyId = static::$client->secretKey()->create('192.168.0.1');
        static::$client->secretKey()->delete($keyId);

        try {
            static::$client->secretKey()->get($keyId);
            $this->fail("Secret key $keyId was not deleted.");
        } catch (Exception $exception) {
            $this->assertEquals(1013, $exception->getCode());
        }
    }

    public function testListEmpty()
    {
        $keys = static::$client->secretKey()->getAll();
        foreach ($keys as $key) {
            static::$client->secretKey()->delete($key->key);
        }

        $keys = static::$client->secretKey()->getAll();
        $this->assertEquals(0, count($keys));
    }
}
