<?php

class CustomerTest extends TestCase
{

    private $_customerProperties = [
        'pname' => 'John Smith',
        'login' => 'john-unit-test',
        'passwd' => 'simple-password',
    ];

    public function testCreate()
    {
        $customer = $this->_client->customer()->create($this->_customerProperties);
        $this->assertInternalType('integer', $customer->id);
        $this->assertGreaterThan(0, $customer->id);

        $this->_client->customer()->delete('id', $customer->id);
    }

    public function testDelete()
    {
        $customer = $this->_client->customer()->create($this->_customerProperties);
        $result = $this->_client->customer()->delete('id', $customer->id);
        $this->assertTrue($result);
    }

}
