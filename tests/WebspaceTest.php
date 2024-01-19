<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\PasswordProvider;

class WebspaceTest extends AbstractTestCase
{
    public function testGetPermissionDescriptor()
    {
        $descriptor = static::$client->webspace()->getPermissionDescriptor();
        $this->assertIsArray($descriptor->permissions);
        $this->assertNotEmpty($descriptor->permissions);
    }

    public function testGetLimitDescriptor()
    {
        $descriptor = static::$client->webspace()->getLimitDescriptor();
        $this->assertIsArray($descriptor->limits);
        $this->assertNotEmpty($descriptor->limits);
    }

    public function testGetDiskUsage()
    {
        $webspace = static::createWebspace();
        $diskusage = static::$client->webspace()->getDiskUsage('id', $webspace->id);

        $this->assertTrue(property_exists($diskusage, 'httpdocs'));

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testGetPhysicalHostingDescriptor()
    {
        $descriptor = static::$client->webspace()->getPhysicalHostingDescriptor();
        $this->assertIsArray($descriptor->properties);
        $this->assertNotEmpty($descriptor->properties);

        $ftpLoginProperty = $descriptor->properties['ftp_login'];
        $this->assertEquals('ftp_login', $ftpLoginProperty->name);
        $this->assertEquals('string', $ftpLoginProperty->type);
    }

    public function testGetPhpSettings()
    {
        $webspace = static::createWebspace();
        $info = static::$client->webspace()->getPhpSettings('id', $webspace->id);

        $this->assertArrayHasKey('open_basedir', $info->properties);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testGetLimits()
    {
        $webspace = static::createWebspace();
        $limits = static::$client->webspace()->getLimits('id', $webspace->id);

        $this->assertIsArray($limits->limits);
        $this->assertNotEmpty($limits->limits);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testCreateWebspace()
    {
        $webspace = static::createWebspace();

        $this->assertGreaterThan(0, $webspace->id);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = static::createWebspace();
        $result = static::$client->webspace()->delete('id', $webspace->id);

        $this->assertTrue($result);
    }

    public function testDeleteByName()
    {
        $webspace = static::createWebspace();
        $result = static::$client->webspace()->delete('name', $webspace->name);

        $this->assertTrue($result);
    }

    public function testRequestCreateWebspace()
    {
        $handlers = static::$client->phpHandler()->getAll();
        $enabledHandlers = array_filter($handlers, function ($handler) {
            return $handler->handlerStatus !== 'disabled';
        });
        $this->assertGreaterThan(0, count($enabledHandlers));
        $handler = current($enabledHandlers);

        $request = [
            'add' => [
                'gen_setup' => [
                    'name' => 'webspace-test-full.test',
                    'htype' => 'vrt_hst',
                    'status' => '0',
                    'ip_address' => [static::getIpAddress()],
                ],
                'hosting' => [
                    'vrt_hst' => [
                        'property' => [
                            [
                                'name' => 'php_handler_id',
                                'value' => $handler->id,
                            ],
                            [
                                'name' => 'ftp_login',
                                'value' => 'testuser',
                            ],
                            [
                                'name' => 'ftp_password',
                                'value' => PasswordProvider::STRONG_PASSWORD,
                            ],
                        ],
                        'ip_address' => static::getIpAddress(),
                    ],
                ],
                'limits' => [
                    'overuse' => 'block',
                    'limit' => [
                        [
                            'name' => 'mbox_quota',
                            'value' => 100,
                        ],
                    ],
                ],
                'prefs' => [
                    'www' => 'false',
                    'stat_ttl' => 6,
                ],
                'performance' => [
                    'bandwidth' => 120,
                    'max_connections' => 10000,
                ],
                'permissions' => [
                    'permission' => [
                        [
                            'name' => 'manage_sh_access',
                            'value' => 'true',
                        ],
                    ],
                ],
                'php-settings' => [
                    'setting' => [
                        [
                            'name' => 'memory_limit',
                            'value' => '128M',
                        ],
                        [
                            'name' => 'safe_mode',
                            'value' => 'false',
                        ],
                    ],
                ],
                'plan-name' => 'Unlimited',
            ],
        ];

        $webspace = static::$client->webspace()->request($request);

        $this->assertGreaterThan(0, $webspace->id);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = static::createWebspace();
        $webspaceInfo = static::$client->webspace()->get('id', $webspace->id);

        $this->assertNotEmpty($webspaceInfo->name);
        $this->assertEquals(0, $webspaceInfo->realSize);
        $this->assertEquals($webspaceInfo->name, $webspaceInfo->asciiName);
        $this->assertIsArray($webspaceInfo->ipAddresses);
        $this->assertEquals(36, strlen($webspaceInfo->guid));
        $this->assertMatchesRegularExpression("/^\d{4}-\d{2}-\d{2}$/", $webspaceInfo->creationDate);
        $this->assertEquals($webspace->id, $webspaceInfo->id);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testEnable()
    {
        $webspace = static::createWebspace();

        static::$client->webspace()->disable('id', $webspace->id);
        static::$client->webspace()->enable('id', $webspace->id);
        $webspaceInfo = static::$client->webspace()->get('id', $webspace->id);
        $this->assertTrue($webspaceInfo->enabled);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testDisable()
    {
        $webspace = static::createWebspace();

        static::$client->webspace()->disable('id', $webspace->id);
        $webspaceInfo = static::$client->webspace()->get('id', $webspace->id);
        $this->assertFalse($webspaceInfo->enabled);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testSetProperties()
    {
        $webspace = static::createWebspace();
        static::$client->webspace()->setProperties('id', $webspace->id, [
            'description' => 'Test Description',
        ]);
        $webspaceInfo = static::$client->webspace()->get('id', $webspace->id);
        $this->assertEquals('Test Description', $webspaceInfo->description);

        static::$client->webspace()->delete('id', $webspace->id);
    }

    public function testIpsAsArray()
    {
        $webspace = static::$client->webspace()->create(
            [
                'name' => "test-ips.test",
                'ip_address' => [static::getIpAddress()],
            ],
            [
                'ftp_login' => "u-ips",
                'ftp_password' => PasswordProvider::STRONG_PASSWORD,
            ]
        );

        $this->assertGreaterThan(0, $webspace->id);

        static::$client->webspace()->delete('id', $webspace->id);
    }
}
