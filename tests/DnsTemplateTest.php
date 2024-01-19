<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class DnsTemplateTest extends AbstractTestCase
{
    private static bool $isDnsSupported;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $serviceStates = static::$client->server()->getServiceStates();
        static::$isDnsSupported = $serviceStates['dns'] && ('running' == $serviceStates['dns']['state']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$isDnsSupported) {
            $this->markTestSkipped('DNS system is not supported.');
        }
    }

    public function testCreate()
    {
        $dns = static::$client->dnsTemplate()->create([
            'type' => 'TXT',
            'host' => 'test.create',
            'value' => 'value',
        ]);
        $this->assertIsInt($dns->id);
        $this->assertGreaterThan(0, $dns->id);
        $this->assertEquals(0, $dns->siteId);
        $this->assertEquals(0, $dns->siteAliasId);
        static::$client->dnsTemplate()->delete('id', $dns->id);
    }

    public function testGetById()
    {
        $dns = static::$client->dnsTemplate()->create([
            'type' => 'TXT',
            'host' => 'test.get.by.id',
            'value' => 'value',
        ]);

        $dnsInfo = static::$client->dnsTemplate()->get('id', $dns->id);
        $this->assertEquals('TXT', $dnsInfo->type);
        $this->assertEquals('value', $dnsInfo->value);

        static::$client->dnsTemplate()->delete('id', $dns->id);
    }

    public function testGetAll()
    {
        $dns = static::$client->dnsTemplate()->create([
            'type' => 'TXT',
            'host' => 'test.get.all',
            'value' => 'value',
        ]);
        $dns2 = static::$client->dnsTemplate()->create([
            'type' => 'TXT',
            'host' => 'test.get.all',
            'value' => 'value2',
        ]);
        $dnsInfo = static::$client->dnsTemplate()->getAll();
        $dsRecords = [];
        foreach ($dnsInfo as $dnsRec) {
            if ('TXT' === $dnsRec->type && 0 === strpos($dnsRec->host, 'test.get.all')) {
                $dsRecords[] = $dnsRec;
            }
        }
        $this->assertCount(2, $dsRecords);

        static::$client->dnsTemplate()->delete('id', $dns->id);
        static::$client->dnsTemplate()->delete('id', $dns2->id);
    }

    public function testDelete()
    {
        $dns = static::$client->dnsTemplate()->create([
            'type' => 'TXT',
            'host' => 'test.delete',
            'value' => 'value',
        ]);
        $result = static::$client->dnsTemplate()->delete('id', $dns->id);
        $this->assertTrue($result);
    }
}
