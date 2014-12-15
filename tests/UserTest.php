<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

class UserTest extends TestCase
{

    private $_customer;

    private $_userProperties = [
        'login' => 'mike-test',
        'passwd' => 'simple-password',
        'owner-guid' => null,
        'name' => 'Mike Black',
        'email' => 'mike@example.com',
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->_customer = $this->_client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'john-unit-test',
            'passwd' => 'simple-password',
        ]);
        $this->_userProperties['owner-guid'] = $this->_customer->guid;
    }

    protected function tearDown()
    {
        $this->_client->customer()->delete('id', $this->_customer->id);
    }

    public function testCreate()
    {
        $user = $this->_client->user()->create('Application User', $this->_userProperties);
        $this->assertInternalType('integer', $user->id);
        $this->assertGreaterThan(0, $user->id);

        $this->_client->user()->delete('guid', $user->guid);
    }

    public function testDelete()
    {
        $user = $this->_client->user()->create('Application User', $this->_userProperties);
        $result = $this->_client->user()->delete('guid', $user->guid);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $user = $this->_client->user()->create('Application User', $this->_userProperties);
        $userInfo = $this->_client->user()->get('guid', $user->guid);
        $this->assertEquals('mike-test', $userInfo->login);
        $this->assertEquals('Mike Black', $userInfo->name);
        $this->assertEquals('mike@example.com', $userInfo->email);
        $this->assertEquals($user->guid, $userInfo->guid);

        $this->_client->user()->delete('guid', $user->guid);
    }

}
