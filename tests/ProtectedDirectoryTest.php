<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskXTest;

class ProtectedDirectoryTest extends TestCase
{
    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$webspace = static::_createWebspace();
    }

    public function testAdd()
    {
        $protectedDirectory = static::$_client->protectedDirectory()->add('/', static::$webspace->id);

        $this->assertIsObject($protectedDirectory);
        $this->assertGreaterThan(0, $protectedDirectory->id);

        static::$_client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1019
     */
    public function testAddInvalidDirectory()
    {
        static::$_client->protectedDirectory()->add('', static::$webspace->id);
    }

    public function testDelete()
    {
        $protectedDirectory = static::$_client->protectedDirectory()->add('/', static::$webspace->id);

        $result = static::$_client->protectedDirectory()->delete('id', $protectedDirectory->id);
        $this->assertTrue($result);
    }

    public function testGetById()
    {
        $protectedDirectory = static::$_client->protectedDirectory()->add('test', static::$webspace->id);

        $foundDirectory = static::$_client->protectedDirectory()->get('id', $protectedDirectory->id);
        $this->assertEquals('test', $foundDirectory->name);

        static::$_client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1013
     */
    public function testGetUnknownDirectory()
    {
        $nonExistentDirectoryId = 99999999;
        static::$_client->protectedDirectory()->get('id', $nonExistentDirectoryId);
    }

    public function testAddUser()
    {
        $protectedDirectory = static::$_client->protectedDirectory()->add('/', static::$webspace->id);

        $user = static::$_client->protectedDirectory()->addUser($protectedDirectory, 'john', 'secret');
        $this->assertGreaterThan(0, $user->id);

        static::$_client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

    public function testDeleteUser()
    {
        $protectedDirectory = static::$_client->protectedDirectory()->add('/', static::$webspace->id);

        $user = static::$_client->protectedDirectory()->addUser($protectedDirectory, 'john', 'secret');
        $result = static::$_client->protectedDirectory()->deleteUser('id', $user->id);
        $this->assertTrue($result);

        static::$_client->protectedDirectory()->delete('id', $protectedDirectory->id);
    }

}
