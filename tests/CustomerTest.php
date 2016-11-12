<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class CustomerTest extends TestCase
{

    private $_customerProperties = [
        'cname' => 'Plesk',
        'pname' => 'John Smith',
        'login' => 'john-unit-test',
        'passwd' => 'simple-password',
        'email' => 'john@smith.com',
        'external-id' => 'link:12345',
        'description' => 'Good guy',
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

    public function testGet()
    {
        $customer = $this->_client->customer()->create($this->_customerProperties);
        $customerInfo = $this->_client->customer()->get('id', $customer->id);
        $this->assertEquals('Plesk', $customerInfo->company);
        $this->assertEquals('John Smith', $customerInfo->personalName);
        $this->assertEquals('john-unit-test', $customerInfo->login);
        $this->assertEquals('john@smith.com', $customerInfo->email);
        $this->assertEquals('Good guy', $customerInfo->description);
        $this->assertEquals('link:12345', $customerInfo->externalId);

        $this->_client->customer()->delete('id', $customer->id);
    }

    public function testGetAll()
    {
        $this->_client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'customer-a',
            'passwd' => 'simple-password',
        ]);
        $this->_client->customer()->create([
            'pname' => 'Mike Black',
            'login' => 'customer-b',
            'passwd' => 'simple-password',
        ]);

        $customersInfo = $this->_client->customer()->getAll();
        $this->assertCount(2, $customersInfo);
        $this->assertEquals('John Smith', $customersInfo[0]->personalName);
        $this->assertEquals('customer-a', $customersInfo[0]->login);

        $this->_client->customer()->delete('login', 'customer-a');
        $this->_client->customer()->delete('login', 'customer-b');
    }

}
