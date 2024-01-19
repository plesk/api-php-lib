<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\PasswordProvider;

class MailTest extends AbstractTestCase
{
    private static \PleskX\Api\Struct\Webspace\Info $webspace;
    private static bool $isMailSupported;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $serviceStates = static::$client->server()->getServiceStates();
        static::$isMailSupported = isset($serviceStates['smtp']) && ('running' == $serviceStates['smtp']['state']);

        if (static::$isMailSupported) {
            static::$webspace = static::createWebspace();
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$isMailSupported) {
            $this->markTestSkipped('Mail system is not supported.');
        }
    }

    public function testCreate()
    {
        $mailname = static::$client->mail()->create(
            'test',
            static::$webspace->id,
            true,
            PasswordProvider::STRONG_PASSWORD
        );

        $this->assertIsInt($mailname->id);
        $this->assertGreaterThan(0, $mailname->id);
        $this->assertEquals('test', $mailname->name);

        static::$client->mail()->delete('name', $mailname->name, static::$webspace->id);
    }

    public function testCreateMultiForwarding()
    {
        $mailname = static::$client->request([
            'mail' => [
                'create' => [
                    'filter' => [
                        'site-id' => static::$webspace->id,
                        'mailname' => [
                            'name' => 'test',
                            'mailbox' => [
                                'enabled' => true,
                            ],
                            'forwarding' => [
                                'enabled' => true,
                                'address' => [
                                    'user1@example.com',
                                    'user2@example.com',
                                ],
                            ],
                            'alias' => [
                                'test1',
                                'test2',
                            ],
                            'password' => [
                                'value' => PasswordProvider::STRONG_PASSWORD,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $mailnameInfo = static::$client->request([
            'mail' => [
                'get_info' => [
                    'filter' => [
                        'site-id' => static::$webspace->id,
                        'name' => 'test',
                    ],
                    'forwarding' => null,
                    'aliases' => null,
                ],
            ],
        ]);

        $this->assertSame(2, count($mailnameInfo->mailname->forwarding->address));
        $this->assertSame(2, count($mailnameInfo->mailname->alias));

        static::$client->mail()->delete('name', 'test', static::$webspace->id);
    }

    public function testDelete()
    {
        $mailname = static::$client->mail()->create('test', static::$webspace->id);

        $result = static::$client->mail()->delete('name', $mailname->name, static::$webspace->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $mailname = static::$client->mail()->create('test', static::$webspace->id);

        $mailnameInfo = static::$client->mail()->get('test', static::$webspace->id);
        $this->assertEquals('test', $mailnameInfo->name);
        $this->assertEquals($mailname->id, $mailnameInfo->id);

        static::$client->mail()->delete('name', $mailname->name, static::$webspace->id);
    }

    public function testGetAll()
    {
        $mailname = static::$client->mail()->create('test', static::$webspace->id);

        $mailnamesInfo = static::$client->mail()->getAll(static::$webspace->id);
        $this->assertCount(1, $mailnamesInfo);
        $this->assertEquals('test', $mailnamesInfo[0]->name);

        static::$client->mail()->delete('name', $mailname->name, static::$webspace->id);
    }

    public function testGetAllWithoutMailnames()
    {
        $mailnamesInfo = static::$client->mail()->getAll(static::$webspace->id);
        $this->assertCount(0, $mailnamesInfo);
    }
}
