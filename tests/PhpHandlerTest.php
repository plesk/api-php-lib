<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskXTest;

class PhpHandlerTest extends AbstractTestCase
{
    public function testGet()
    {
        $handler = static::$client->phpHandler()->get();

        $this->assertObjectHasAttribute('type', $handler);
    }

    public function testGetAll()
    {
        $handlers = static::$client->phpHandler()->getAll();

        $this->assertIsArray($handlers);
        $this->assertNotEmpty($handlers);

        $handler = current($handlers);

        $this->assertIsObject($handler);
        $this->assertObjectHasAttribute('type', $handler);
    }

    public function testGetUnknownHandlerThrowsException()
    {
        $this->expectException(\PleskX\Api\Exception::class);
        $this->expectExceptionMessage('Php handler does not exists');

        static::$client->phpHandler()->get('id', 'this-handler-does-not-exist');
    }
}
