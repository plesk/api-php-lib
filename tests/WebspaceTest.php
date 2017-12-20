<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class WebspaceTest extends TestCase
{

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createDomain()
    {
        return static::$_client->webspace()->create([
            'name' => 'example-test.dom',
            'ip_address' => static::_getIpAddress(),
        ]);
    }

    public function testGetPermissionDescriptor()
    {
        $descriptor = static::$_client->webspace()->getPermissionDescriptor();
        $this->assertInternalType('array', $descriptor->permissions);
        $this->assertGreaterThan(0, count($descriptor->permissions));
    }

    public function testGetLimitDescriptor()
    {
        $descriptor = static::$_client->webspace()->getLimitDescriptor();
        $this->assertInternalType('array', $descriptor->limits);
        $this->assertGreaterThan(0, count($descriptor->limits));
    }

    public function testGetPhysicalHostingDescriptor()
    {
        $descriptor = static::$_client->webspace()->getPhysicalHostingDescriptor();
        $this->assertInternalType('array', $descriptor->properties);
        $this->assertGreaterThan(0, count($descriptor->properties));

        $ftpLoginProperty = $descriptor->properties['ftp_login'];
        $this->assertEquals('ftp_login', $ftpLoginProperty->name);
        $this->assertEquals('string', $ftpLoginProperty->type);
    }

    public function testCreate()
    {
        $domain = $this->_createDomain();
        $this->assertInternalType('integer', $domain->id);
        $this->assertGreaterThan(0, $domain->id);

        static::$_client->webspace()->delete('id', $domain->id);
    }

    public function testCreateWebspace()
    {
        $webspace = static::$_client->webspace()->create([
            'name' => 'example-test.dom',
            'ip_address' => static::_getIpAddress(),
        ], [
            'ftp_login' => 'test-login',
            'ftp_password' => 'test-password',
        ]);
        static::$_client->webspace()->delete('id', $webspace->id);
    }

    public function testRequestCreateWebspace()
    {
        $webspace = static::$_client->webspace()->request([
            'add' => [
                'gen_setup' => [
                    'name' => 'example-second-test.dom',
                    'htype' => 'vrt_hst',
                    'status' => '0',
                    'ip_address' => [static::_getIpAddress()],
                ],
                'hosting' => [
                    'vrt_hst' => [
                        'property' => [
                            [
                                'name' => 'php_handler_id',
                                'value' => 'fastcgi',
                            ],
                            [
                                'name' => 'ftp_login',
                                'value' => 'ftp-login-test-1',
                            ],
                            [
                                'name' => 'ftp_password',
                                'value' => 'ftp-password-test-1',
                            ],
                        ],
                        'ip_address' => static::_getIpAddress(),
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
        ]);
        static::$_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $domain = $this->_createDomain();
        $result = static::$_client->webspace()->delete('id', $domain->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $domain = $this->_createDomain();
        $domainInfo = static::$_client->webspace()->get('id', $domain->id);
        $this->assertEquals('example-test.dom', $domainInfo->name);

        static::$_client->webspace()->delete('id', $domain->id);
    }

}
