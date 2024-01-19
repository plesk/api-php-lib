<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class SiteAliasTest extends AbstractTestCase
{
    private static \PleskX\Api\Struct\Webspace\Info $webspace;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::createWebspace();
    }

    private function createSiteAlias($name, array $properties = []): \PleskX\Api\Struct\SiteAlias\Info
    {
        $properties = array_merge([
            'name' => $name,
            'site-id' => static::$webspace->id,
        ], $properties);

        return static::$client->siteAlias()->create($properties);
    }

    public function testCreate()
    {
        $siteAlias = $this->createSiteAlias('alias.dom');

        $this->assertIsNumeric($siteAlias->id);
        $this->assertGreaterThan(0, $siteAlias->id);

        static::$client->siteAlias()->delete('id', $siteAlias->id);
    }

    public function testDelete()
    {
        $siteAlias = $this->createSiteAlias('alias.dom');

        $result = static::$client->siteAlias()->delete('id', $siteAlias->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $siteAlias = $this->createSiteAlias('alias.dom');

        $siteAliasInfo = static::$client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals('alias.dom', $siteAliasInfo->name);

        static::$client->siteAlias()->delete('id', $siteAlias->id);
    }

    public function testGetAll()
    {
        $siteAlias = $this->createSiteAlias('alias.dom');
        $siteAlias2 = $this->createSiteAlias('alias2.dom');

        $siteAliasInfo = static::$client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals('alias.dom', $siteAliasInfo->name);

        $siteAliasesInfo = static::$client->siteAlias()->getAll('site-id', self::$webspace->id);
        $this->assertCount(2, $siteAliasesInfo);
        $this->assertEquals('alias.dom', $siteAliasesInfo[0]->name);
        $this->assertEquals('alias.dom', $siteAliasesInfo[0]->asciiName);

        static::$client->siteAlias()->delete('id', $siteAlias->id);
        static::$client->siteAlias()->delete('id', $siteAlias2->id);
    }
}
