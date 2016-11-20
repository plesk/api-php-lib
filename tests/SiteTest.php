<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class SiteTest extends TestCase
{
    /**
     * @var \PleskX\Api\Struct\Webspace\Info
     */
    private static $_webspace;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$_webspace = static::_createWebspace('example.dom');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        static::$_client->webspace()->delete('id', static::$_webspace->id);
    }

    private function _createSite($name)
    {
        return static::$_client->site()->create([
            'name' => $name,
            'webspace-id' => static::$_webspace->id,
        ]);
    }

    public function testCreate()
    {
        $site = $this->_createSite('addon.dom');

        $this->assertInternalType('integer', $site->id);
        $this->assertGreaterThan(0, $site->id);

        static::$_client->site()->delete('id', $site->id);
    }

    public function testDelete()
    {
        $site = $this->_createSite('addon.dom');

        $result = static::$_client->site()->delete('id', $site->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $site = $this->_createSite('addon.dom');

        $siteInfo = static::$_client->site()->get('id', $site->id);
        $this->assertEquals('addon.dom', $siteInfo->name);

        static::$_client->site()->delete('id', $site->id);
    }

    public function testGetAll()
    {
        $site = $this->_createSite('addon.dom');
        $site2 = $this->_createSite('addon2.dom');

        $sitesInfo = static::$_client->site()->getAll();
        $this->assertCount(2, $sitesInfo);
        $this->assertEquals('addon.dom', $sitesInfo[0]->name);
        $this->assertEquals('addon.dom', $sitesInfo[0]->asciiName);

        static::$_client->site()->delete('id', $site->id);
        static::$_client->site()->delete('id', $site2->id);
    }

}
