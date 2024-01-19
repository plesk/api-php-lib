<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class PhpHandlerTest extends AbstractTestCase
{
    public function testGet()
    {
        $handler = static::$client->phpHandler()->get();

        $this->assertTrue(property_exists($handler, 'type'));
    }

    public function testGetAll()
    {
        $handlers = static::$client->phpHandler()->getAll();

        $this->assertIsArray($handlers);
        $this->assertNotEmpty($handlers);

        $handler = current($handlers);

        $this->assertIsObject($handler);
        $this->assertTrue(property_exists($handler, 'type'));
    }

    public function testGetUnknownHandlerThrowsException()
    {
        $this->expectException(\PleskX\Api\Exception::class);
        $this->expectExceptionMessage('Php handler does not exists');

        static::$client->phpHandler()->get('id', 'this-handler-does-not-exist');
    }
}
