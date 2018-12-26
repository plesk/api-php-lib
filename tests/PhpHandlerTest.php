<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskXTest;

use PleskX\Api\Struct\PhpHandler\Info;

class PhpHandlerTest extends TestCase
{
    public function testGet()
    {
        $handler = static::$_client->phpHandler()->get(null, null);

        $this->assertInstanceOf(Info::class, $handler);
    }

    public function testGetAll()
    {
        $handlers = static::$_client->phpHandler()->getAll();

        $this->assertIsArray($handlers);
        $this->assertNotEmpty($handlers);

        $handler = current($handlers);
        $this->assertInstanceOf(Info::class, $handler);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionMessage Php handler does not exists
     */
    public function testGetUnknownHandlerThrowsException()
    {
        static::$_client->phpHandler()->get('id', 'this-handler-does-not-exist');
    }
}
