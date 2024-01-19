<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\KeyLimitChecker;
use PleskXTest\Utility\PasswordProvider;

class ResellerTest extends AbstractTestCase
{
    private array $resellerProperties;

    public function setUp(): void
    {
        $this->resellerProperties = [
            'pname' => 'John Reseller',
            'login' => 'reseller-unit-test',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
        ];
    }

    public function testCreate()
    {
        $reseller = static::$client->reseller()->create($this->resellerProperties);
        $this->assertIsInt($reseller->id);
        $this->assertGreaterThan(0, $reseller->id);

        static::$client->reseller()->delete('id', $reseller->id);
    }

    public function testDelete()
    {
        $reseller = static::$client->reseller()->create($this->resellerProperties);
        $result = static::$client->reseller()->delete('id', $reseller->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $reseller = static::$client->reseller()->create($this->resellerProperties);
        $resellerInfo = static::$client->reseller()->get('id', $reseller->id);
        $this->assertEquals('John Reseller', $resellerInfo->personalName);
        $this->assertEquals('reseller-unit-test', $resellerInfo->login);
        $this->assertGreaterThan(0, count($resellerInfo->permissions));
        $this->assertEquals($reseller->id, $resellerInfo->id);

        static::$client->reseller()->delete('id', $reseller->id);
    }

    public function testGetAll()
    {
        $keyInfo = static::$client->server()->getKeyInfo();

        if (!KeyLimitChecker::checkByType($keyInfo, KeyLimitChecker::LIMIT_RESELLERS, 2)) {
            $this->markTestSkipped('License does not allow to create more than 1 reseller.');
        }

        static::$client->reseller()->create([
            'pname' => 'John Reseller',
            'login' => 'reseller-a',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
        ]);
        static::$client->reseller()->create([
            'pname' => 'Mike Reseller',
            'login' => 'reseller-b',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
        ]);

        $resellersInfo = static::$client->reseller()->getAll();
        $this->assertCount(2, $resellersInfo);
        $this->assertEquals('John Reseller', $resellersInfo[0]->personalName);
        $this->assertEquals('reseller-a', $resellersInfo[0]->login);

        static::$client->reseller()->delete('login', 'reseller-a');
        static::$client->reseller()->delete('login', 'reseller-b');
    }

    public function testGetAllEmpty()
    {
        $resellersInfo = static::$client->reseller()->getAll();
        $this->assertCount(0, $resellersInfo);
    }
}
