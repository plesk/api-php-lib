<?php
// Copyright 1999-2019. Plesk International GmbH.
namespace PleskXTest;

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
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $this->assertInternalType('integer', $customer->id);
        $this->assertGreaterThan(0, $customer->id);

        static::$_client->customer()->delete('id', $customer->id);
    }

    public function testDelete()
    {
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $result = static::$_client->customer()->delete('id', $customer->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $customer = static::$_client->customer()->create($this->_customerProperties);
        $customerInfo = static::$_client->customer()->get('id', $customer->id);
        $this->assertEquals('Plesk', $customerInfo->company);
        $this->assertEquals('John Smith', $customerInfo->personalName);
        $this->assertEquals('john-unit-test', $customerInfo->login);
        $this->assertEquals('john@smith.com', $customerInfo->email);
        $this->assertEquals('Good guy', $customerInfo->description);
        $this->assertEquals('link:12345', $customerInfo->externalId);

        static::$_client->customer()->delete('id', $customer->id);
    }

    public function testGetAll()
    {
        $keyInfo = static::$_client->server()->getKeyInfo();

        if ((int)$keyInfo['lim_cl'] < 2) {
            $this->markTestSkipped('License does not allow to create more than 1 customer.');
        }

        static::$_client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'customer-a',
            'passwd' => 'simple-password',
        ]);
        static::$_client->customer()->create([
            'pname' => 'Mike Black',
            'login' => 'customer-b',
            'passwd' => 'simple-password',
        ]);

        $customersInfo = static::$_client->customer()->getAll();
        $this->assertIsArray($customersInfo);

        $customersCheck = array_filter($customersInfo, function ($value) {
            return $value->personalName === 'John Smith' || $value->personalName === 'Mike Black';
        });
        $this->assertCount(2, $customersCheck);

        static::$_client->customer()->delete('login', 'customer-a');
        static::$_client->customer()->delete('login', 'customer-b');
    }
}
