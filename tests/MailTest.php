<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class MailTest extends TestCase
{

    /**
     * @var \PleskX\Api\Struct\Webspace\Info
     */
    private static $_webspace;

    /**
     * @var bool
     */
    private static $_isMailSupported;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $serviceStates = static::$_client->server()->getServiceStates();
        static::$_isMailSupported = $serviceStates['smtp'] && ('running' == $serviceStates['smtp']['state']);

        static::$_webspace = static::_createWebspace('example.dom');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        static::$_client->webspace()->delete('id', static::$_webspace->id);
    }

    protected function setUp()
    {
        parent::setUp();

        if (!static::$_isMailSupported) {
            $this->markTestSkipped('Mail system is not supported.');
        }
    }

    public function testCreate()
    {
        $mailname = static::$_client->mail()->create('test', static::$_webspace->id, true, 'secret');

        $this->assertInternalType('integer', $mailname->id);
        $this->assertGreaterThan(0, $mailname->id);
        $this->assertEquals('test', $mailname->name);

        static::$_client->mail()->delete('name', $mailname->name, static::$_webspace->id);
    }

    public function testDelete()
    {
        $mailname = static::$_client->mail()->create('test', static::$_webspace->id);

        $result = static::$_client->mail()->delete('name', $mailname->name, static::$_webspace->id);
        $this->assertTrue($result);
    }

}
