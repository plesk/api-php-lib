<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class SubdomainTest extends AbstractTestCase
{
    private static \PleskX\Api\Struct\Webspace\Info $webspace;
    private static string $webspaceName;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::createWebspace();
        $webspaceInfo = static::$client->webspace()->get('id', static::$webspace->id);
        static::$webspaceName = $webspaceInfo->name;
    }

    private function createSubdomain(string $name): \PleskX\Api\Struct\Subdomain\Info
    {
        return static::$client->subdomain()->create([
            'parent' => static::$webspaceName,
            'name' => $name,
            'property' => [
                'www_root' => $name,
            ],
        ]);
    }

    public function testCreate()
    {
        $subdomain = $this->createSubdomain('sub');

        $this->assertIsInt($subdomain->id);
        $this->assertGreaterThan(0, $subdomain->id);

        static::$client->subdomain()->delete('id', $subdomain->id);
    }

    public function testDelete()
    {
        $subdomain = $this->createSubdomain('sub');

        $result = static::$client->subdomain()->delete('id', $subdomain->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $name = 'sub';
        $subdomain = $this->createSubdomain($name);

        $subdomainInfo = static::$client->subdomain()->get('id', $subdomain->id);
        $this->assertEquals($name . '.' . $subdomainInfo->parent, $subdomainInfo->name);
        $this->assertTrue(false !== strpos($subdomainInfo->properties['www_root'], $name));
        $this->assertEquals($subdomain->id, $subdomainInfo->id);

        static::$client->subdomain()->delete('id', $subdomain->id);
    }

    public function testGetAll()
    {
        $name = 'sub';
        $name2 = 'sub2';
        $subdomain = $this->createSubdomain($name);
        $subdomain2 = $this->createSubdomain($name2);

        $subdomainsInfo = static::$client->subdomain()->getAll();
        $this->assertCount(2, $subdomainsInfo);
        $this->assertEquals($name . '.' . $subdomainsInfo[0]->parent, $subdomainsInfo[0]->name);
        $this->assertTrue(false !== strpos($subdomainsInfo[0]->properties['www_root'], $name));
        $this->assertEquals($name2 . '.' . $subdomainsInfo[1]->parent, $subdomainsInfo[1]->name);
        $this->assertTrue(false !== strpos($subdomainsInfo[1]->properties['www_root'], $name2));

        static::$client->subdomain()->delete('id', $subdomain->id);
        static::$client->subdomain()->delete('id', $subdomain2->id);

        $subdomainsInfo = static::$client->subdomain()->getAll();
        $this->assertEmpty($subdomainsInfo);
    }
}
