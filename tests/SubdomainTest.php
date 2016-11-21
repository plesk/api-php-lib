<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class SubdomainTest extends TestCase
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

    /**
     * @param string $name
     * @param string $webspaceName
     * @return \PleskX\Api\Struct\Subdomain\Info
     */
    private function _createSubdomain($name)
    {
        return static::$_client->subdomain()->create([
            'parent' => 'example.dom',
            'name' => $name,
        ]);
    }

    public function testCreate()
    {
        $subdomain = $this->_createSubdomain('sub');

        $this->assertInternalType('integer', $subdomain->id);
        $this->assertGreaterThan(0, $subdomain->id);

        static::$_client->subdomain()->delete('id', $subdomain->id);
    }

    public function testDelete()
    {
        $subdomain = $this->_createSubdomain('sub');

        $result = static::$_client->subdomain()->delete('id', $subdomain->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $subdomain = $this->_createSubdomain('sub');

        $subdomainInfo = static::$_client->subdomain()->get('id', $subdomain->id);
        $name = explode('.', $subdomainInfo->name);
        $parent = explode('.', $subdomainInfo->parent);
        $this->assertEquals('sub', reset(array_diff($name, $parent)));

        static::$_client->subdomain()->delete('id', $subdomain->id);
    }

    public function testGetAll()
    {
        $subdomain = $this->_createSubdomain('sub');
        $subdomain2 = $this->_createSubdomain('sub2');

        $subdomainInfo = static::$_client->subdomain()->getAll();
        $this->assertCount(2, $subdomainInfo);
        $name = explode('.', $subdomainInfo[0]->name);
        $parent = explode('.', $subdomainInfo[0]->parent);
        $name2 = explode('.', $subdomainInfo[1]->name);
        $parent2 = explode('.', $subdomainInfo[1]->parent);
        $this->assertEquals('sub', reset(array_diff($name, $parent)));
        $this->assertEquals('sub2', reset(array_diff($name2, $parent2)));

        static::$_client->subdomain()->delete('id', $subdomain->id);
        static::$_client->subdomain()->delete('id', $subdomain2->id);
    }
}
