<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskXTest;

class SiteAliasTest extends TestCase
{
    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::_createWebspace();
    }

    private function _createSiteAlias($name, array $properties = [])
    {
        $properties = array_merge([
            'name' => $name,
            'site-id' => static::$webspace->id,
        ], $properties);

        return static::$_client->siteAlias()->create($properties);
    }

    public function testCreate()
    {
        $siteAlias = $this->_createSiteAlias('alias.dom');

        $this->assertIsNumeric($siteAlias->id);
        $this->assertGreaterThan(0, $siteAlias->id);

        static::$_client->siteAlias()->delete('id', $siteAlias->id);
    }

    public function testDelete()
    {
        $siteAlias = $this->_createSiteAlias('alias.dom');

        $result = static::$_client->siteAlias()->delete('id', $siteAlias->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $siteAlias = $this->_createSiteAlias('alias.dom');

        $siteAliasInfo = static::$_client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals('alias.dom', $siteAliasInfo->name);

        static::$_client->siteAlias()->delete('id', $siteAlias->id);
    }

    public function testGetAll()
    {
        $siteAlias = $this->_createSiteAlias('alias.dom');
        $siteAlias2 = $this->_createSiteAlias('alias2.dom');

        $siteAliasInfo = static::$_client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals('alias.dom', $siteAliasInfo->name);

        $siteAliasesInfo = static::$_client->siteAlias()->getAll('site-id', self::$webspace->id);
        $this->assertCount(2, $siteAliasesInfo);
        $this->assertEquals('alias.dom', $siteAliasesInfo[0]->name);
        $this->assertEquals('alias.dom', $siteAliasesInfo[0]->asciiName);

        static::$_client->siteAlias()->delete('id', $siteAlias->id);
        static::$_client->siteAlias()->delete('id', $siteAlias2->id);
    }
}
