<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class ResellerTest extends TestCase
{

    private $_resellerProperties = [
        'pname' => 'John Reseller',
        'login' => 'reseller-unit-test',
        'passwd' => 'simple-password',
    ];

    public function testCreate()
    {
        $reseller = static::$_client->reseller()->create($this->_resellerProperties);
        $this->assertInternalType('integer', $reseller->id);
        $this->assertGreaterThan(0, $reseller->id);

        static::$_client->reseller()->delete('id', $reseller->id);
    }

    public function testDelete()
    {
        $reseller = static::$_client->reseller()->create($this->_resellerProperties);
        $result = static::$_client->reseller()->delete('id', $reseller->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $reseller = static::$_client->reseller()->create($this->_resellerProperties);
        $resellerInfo = static::$_client->reseller()->get('id', $reseller->id);
        $this->assertEquals('John Reseller', $resellerInfo->personalName);
        $this->assertEquals('reseller-unit-test', $resellerInfo->login);

        static::$_client->reseller()->delete('id', $reseller->id);
    }

}
