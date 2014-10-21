<?php

class ResellerTest extends TestCase
{

    private $_resellerProperties = [
        'pname' => 'John Reseller',
        'login' => 'reseller-unit-test',
        'passwd' => 'simple-password',
    ];

    public function testCreate()
    {
        $reseller = $this->_client->reseller()->create($this->_resellerProperties);
        $this->assertInternalType('integer', $reseller->id);
        $this->assertGreaterThan(0, $reseller->id);

        $this->_client->reseller()->delete('id', $reseller->id);
    }

    public function testDelete()
    {
        $reseller = $this->_client->reseller()->create($this->_resellerProperties);
        $result = $this->_client->reseller()->delete('id', $reseller->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $reseller = $this->_client->reseller()->create($this->_resellerProperties);
        $resellerInfo = $this->_client->reseller()->get('id', $reseller->id);
        $this->assertEquals('John Reseller', $resellerInfo->personalName);
        $this->assertEquals('reseller-unit-test', $resellerInfo->login);

        $this->_client->reseller()->delete('id', $reseller->id);
    }

}
