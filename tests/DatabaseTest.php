<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class DatabaseTest extends TestCase
{
    /**
     * @var \PleskX\Api\Struct\Webspace\Info
     */
    private $_webspace;

    protected function setUp()
    {
        parent::setUp();
        $this->_webspace = $this->_client->webspace()->create([
            'name' => 'example.tld',
            'ip_address' => $this->_getIpAddress(),
        ], [
            'ftp_login' => 'test-login',
            'ftp_password' => 'test-password',
        ]);
    }

    protected function tearDown()
    {
        $this->_client->webspace()->delete('id', $this->_webspace->id);
    }

    public function testCreate()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $this->_client->database()->delete('id', $database->id);
    }

    public function testCreateUser()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);
        $this->_client->database()->deleteUser('id', $user->id);
        $this->_client->database()->delete('id', $database->id);

    }

    public function testGetById()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);

        $db = $this->_client->database()->get('id', $database->id);
        $this->assertEquals('test1', $db->name);
        $this->assertEquals('mysql', $db->type);
        $this->assertEquals($this->_webspace->id, $db->webspaceId);
        $this->assertEquals(1, $db->dbServerId);

        $this->_client->database()->delete('id', $database->id);
    }

    public function testGetAllByWebspaceId()
    {
        $db1 = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $db2 = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test2',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $databases = $this->_client->database()->getAll('webspace-id', $this->_webspace->id);
        $this->assertEquals('test1', $databases[0]->name);
        $this->assertEquals('test2', $databases[1]->name);
        $this->assertEquals($this->_webspace->id, $databases[0]->webspaceId);
        $this->assertEquals(1, $databases[1]->dbServerId);

        $this->_client->database()->delete('id', $db1->id);
        $this->_client->database()->delete('id', $db2->id);
    }

    public function testGetUserById()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);

        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $dbUser = $this->_client->database()->getUser('id', $user->id);
        $this->assertEquals('test_user1', $dbUser->login);
        $this->assertEquals($database->id, $dbUser->dbId);

        $this->_client->database()->deleteUser('id', $user->id);
        $this->_client->database()->delete('id', $database->id);
    }

    public function testGetAllUsersByDbId()
    {
        $db1 = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $db2 = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test2',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user1 = $this->_createUser([
            'db-id' => $db1->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $user2 = $this->_createUser([
            'db-id' => $db1->id,
            'login' => 'test_user2',
            'password' => 'setup1Q',
        ]);

        $user3 = $this->_createUser([
            'db-id' => $db2->id,
            'login' => 'test_user3',
            'password' => 'setup1Q',
        ]);

        $dbUsers = $this->_client->database()->getAllUsers('db-id', $db1->id);
        $this->assertEquals(2, count($dbUsers));
        $this->assertEquals('test_user1', $dbUsers[0]->login);
        $this->assertEquals('test_user2', $dbUsers[1]->login);

        $this->_client->database()->deleteUser('id', $user1->id);
        $this->_client->database()->deleteUser('id', $user2->id);
        $this->_client->database()->deleteUser('id', $user3->id);
        $this->_client->database()->delete('id', $db1->id);
        $this->_client->database()->delete('id', $db2->id);
    }

    public function testDelete()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $result = $this->_client->database()->delete('id', $database->id);
        $this->assertTrue($result);
    }

    public function testDeleteUser()
    {
        $database = $this->_createDatabase([
            'webspace-id' => $this->_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $result = $this->_client->database()->deleteUser('id', $user->id);
        $this->assertTrue($result);
        $this->_client->database()->delete('id', $database->id);
    }

    /**
     * @param array $params
     * @return \PleskX\Api\Struct\Database\Info
     */
    private function _createDatabase(array $params)
    {
        $database = $this->_client->database()->create($params);
        $this->assertInternalType('integer', $database->id);
        $this->assertGreaterThan(0, $database->id);
        return $database;
    }

    /**
     * @param array $params
     * @return \PleskX\Api\Struct\Database\UserInfo
     */
    private function _createUser(array $params)
    {
        $user = $this->_client->database()->createUser($params);
        $this->assertInternalType('integer', $user->id);
        $this->assertGreaterThan(0, $user->id);
        return $user;
    }
}
