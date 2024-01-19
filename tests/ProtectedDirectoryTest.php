<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\PasswordProvider;

class ProtectedDirectoryTest extends AbstractTestCase
{
    private static \PleskX\Api\Struct\Webspace\Info $webspace;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::createWebspace();
    }

    public function testAdd()
    {
        $protectedDirectory = static::$client->protectedDirectory()->add('/', static::$webspace->id);

        $this->assertIsObject($protectedDirectory);
        $this->assertGreaterThan(0, $protectedDirectory->id);

        static::$client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    public function testAddInvalidDirectory()
    {
        $this->expectException(\PleskX\Api\Exception::class);
        $this->expectExceptionCode(1019);

        static::$client->protectedDirectory()->add('', static::$webspace->id);
    }

    public function testDelete()
    {
        $protectedDirectory = static::$client->protectedDirectory()->add('/', static::$webspace->id);

        $result = static::$client->protectedDirectory()->delete('id', $protectedDirectory->id);
        $this->assertTrue($result);
    }

    public function testGetById()
    {
        $protectedDirectory = static::$client->protectedDirectory()->add('test', static::$webspace->id);

        $foundDirectory = static::$client->protectedDirectory()->get('id', $protectedDirectory->id);
        $this->assertEquals('test', $foundDirectory->name);

        static::$client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    public function testGetUnknownDirectory()
    {
        $this->expectException(\PleskX\Api\Exception::class);
        $this->expectExceptionCode(1013);

        $nonExistentDirectoryId = 99999999;
        static::$client->protectedDirectory()->get('id', $nonExistentDirectoryId);
    }

    public function testAddUser()
    {
        $protectedDirectory = static::$client->protectedDirectory()->add('/', static::$webspace->id);

        $user = static::$client->protectedDirectory()->addUser(
            $protectedDirectory,
            'john',
            PasswordProvider::STRONG_PASSWORD
        );
        $this->assertGreaterThan(0, $user->id);

        static::$client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    public function testDeleteUser()
    {
        $protectedDirectory = static::$client->protectedDirectory()->add('/', static::$webspace->id);

        $user = static::$client->protectedDirectory()->addUser(
            $protectedDirectory,
            'john',
            PasswordProvider::STRONG_PASSWORD
        );
        $result = static::$client->protectedDirectory()->deleteUser('id', $user->id);
        $this->assertTrue($result);

        static::$client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }
}
