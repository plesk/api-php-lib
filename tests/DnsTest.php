<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskXTest;

class DnsTest extends TestCase
{
    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    private static $isDnsSupported;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $serviceStates = static::$_client->server()->getServiceStates();
        static::$isDnsSupported = isset($serviceStates['dns']) && ('running' == $serviceStates['dns']['state']);

        if (static::$isDnsSupported) {
            static::$webspace = static::_createWebspace();
        }
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
        $dns = static::$_client->dns()->create([
            'site-id' => static::$webspace->id,
            'type' => 'TXT',
            'host' => 'host',
            'value' => 'value',
        ]);
        $this->assertIsInt($dns->id);
        $this->assertGreaterThan(0, $dns->id);
        static::$_client->dns()->delete('id', $dns->id);
    }

    public function testGetById()
    {
        $dns = static::$_client->dns()->create([
            'site-id' => static::$webspace->id,
            'type' => 'TXT',
            'host' => '',
            'value' => 'value',
        ]);

        $dnsInfo = static::$_client->dns()->get('id', $dns->id);
        $this->assertEquals('TXT', $dnsInfo->type);
        $this->assertEquals(static::$webspace->id, $dnsInfo->siteId);
        $this->assertEquals('value', $dnsInfo->value);

        static::$_client->dns()->delete('id', $dns->id);
    }

    public function testGetAllByWebspaceId()
    {
        $dns = static::$_client->dns()->create([
            'site-id' => static::$webspace->id,
            'type' => 'DS',
            'host' => '',
            'value' => '60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',
        ]);
        $dns2 = static::$_client->dns()->create([
            'site-id' => static::$webspace->id,
            'type' => 'DS',
            'host' => '',
            'value' => '60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292119',
        ]);
        $dnsInfo = static::$_client->dns()->getAll('site-id', static::$webspace->id);
        $dsRecords = [];
        foreach ($dnsInfo as $dnsRec) {
            if ('DS' == $dnsRec->type) {
                $dsRecords[] = $dnsRec;
            }
        }
        $this->assertEquals(2, count($dsRecords));
        foreach ($dsRecords as $dsRecord) {
            $this->assertEquals(static::$webspace->id, $dsRecord->siteId);
        }

        static::$_client->dns()->delete('id', $dns->id);
        static::$_client->dns()->delete('id', $dns2->id);
    }

    public function testDelete()
    {
        $dns = static::$_client->dns()->create([
            'site-id' => static::$webspace->id,
            'type' => 'TXT',
            'host' => 'host',
            'value' => 'value',
        ]);
        $result = static::$_client->dns()->delete('id', $dns->id);
        $this->assertTrue($result);
    }
}
