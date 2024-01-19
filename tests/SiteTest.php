<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\KeyLimitChecker;

class SiteTest extends AbstractTestCase
{
    private static \PleskX\Api\Struct\Webspace\Info $webspace;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::createWebspace();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $keyInfo = static::$client->server()->getKeyInfo();

        if (!KeyLimitChecker::checkByType($keyInfo, KeyLimitChecker::LIMIT_DOMAINS, 2)) {
            $this->markTestSkipped('License does not allow to create more than 1 domain.');
        }
    }

    private function createSite($name, array $properties = []): \PleskX\Api\Struct\Site\Info
    {
        $properties = array_merge([
            'name' => $name,
            'webspace-id' => static::$webspace->id,
        ], $properties);

        return static::$client->site()->create($properties);
    }

    public function testCreate()
    {
        $site = $this->createSite('addon.dom');

        $this->assertIsNumeric($site->id);
        $this->assertGreaterThan(0, $site->id);

        static::$client->site()->delete('id', $site->id);
    }

    public function testDelete()
    {
        $site = $this->createSite('addon.dom');

        $result = static::$client->site()->delete('id', $site->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $site = $this->createSite('addon.dom');

        $siteInfo = static::$client->site()->get('id', $site->id);
        $this->assertEquals('addon.dom', $siteInfo->name);
        $this->assertMatchesRegularExpression("/^\d{4}-\d{2}-\d{2}$/", $siteInfo->creationDate);
        $this->assertEquals(36, strlen($siteInfo->guid));

        $siteGuid = $siteInfo->guid;
        $siteInfo = static::$client->site()->get('guid', $siteGuid);
        $this->assertEquals($site->id, $siteInfo->id);

        static::$client->site()->delete('id', $site->id);

        $siteInfo = static::$client->site()->get('parent-id', static::$webspace->id);
        $this->assertNull($siteInfo);
    }

    public function testGetHostingWoHosting()
    {
        $site = $this->createSite('addon.dom');

        $siteHosting = static::$client->site()->getHosting('id', $site->id);
        $this->assertNull($siteHosting);

        static::$client->site()->delete('id', $site->id);
    }

    public function testGetHostingWithHosting()
    {
        $properties = [
            'hosting' => [
                'www_root' => 'addon.dom',
            ],
        ];
        $site = $this->createSite('addon.dom', $properties);

        $siteHosting = static::$client->site()->getHosting('id', $site->id);
        $this->assertArrayHasKey('www_root', $siteHosting->properties);
        $this->assertStringEndsWith('addon.dom', $siteHosting->properties['www_root']);

        static::$client->site()->delete('id', $site->id);
    }

    public function testGetAll()
    {
        $site = $this->createSite('addon.dom');
        $site2 = $this->createSite('addon2.dom');

        $sitesInfo = static::$client->site()->getAll();
        $this->assertCount(2, $sitesInfo);
        $this->assertEquals('addon.dom', $sitesInfo[0]->name);
        $this->assertEquals('addon.dom', $sitesInfo[0]->asciiName);
        $this->assertEquals($site->id, $sitesInfo[0]->id);

        static::$client->site()->delete('id', $site->id);
        static::$client->site()->delete('id', $site2->id);
    }

    public function testGetAllWithoutSites()
    {
        $sitesInfo = static::$client->site()->getAll();
        $this->assertEmpty($sitesInfo);
    }
}
