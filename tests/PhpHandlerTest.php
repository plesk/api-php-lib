<?php
// Copyright 1999-2018. Parallels IP Holdings GmbH.

class PhpHandlerTest extends TestCase
{
    const PHP_HANDLER_ID = 'fpm';

    public function testGet()
    {
        $handler = static::$_client->phpHandler()->get('id', self::PHP_HANDLER_ID);

        $this->assertSame(self::PHP_HANDLER_ID, $handler->id);
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
