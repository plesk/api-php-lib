<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

use PleskXTest\Utility\KeyLimitChecker;
use PleskXTest\Utility\PasswordProvider;

class CustomerTest extends AbstractTestCase
{
    private array $customerProperties;

    public function setUp(): void
    {
        $this->customerProperties = [
            'cname' => 'Plesk',
            'pname' => 'John Smith',
            'login' => 'john-unit-test',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
            'email' => 'john@smith.com',
            'external-id' => 'link:12345',
            'description' => 'Good guy',
        ];
    }

    public function testCreate()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        $this->assertIsInt($customer->id);
        $this->assertGreaterThan(0, $customer->id);

        static::$client->customer()->delete('id', $customer->id);
    }

    public function testDelete()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        $result = static::$client->customer()->delete('id', $customer->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        $customerInfo = static::$client->customer()->get('id', $customer->id);
        $this->assertEquals('Plesk', $customerInfo->company);
        $this->assertEquals('John Smith', $customerInfo->personalName);
        $this->assertEquals('john-unit-test', $customerInfo->login);
        $this->assertEquals('john@smith.com', $customerInfo->email);
        $this->assertEquals('Good guy', $customerInfo->description);
        $this->assertEquals('link:12345', $customerInfo->externalId);
        $this->assertEquals($customer->id, $customerInfo->id);

        static::$client->customer()->delete('id', $customer->id);
    }

    public function testGetAll()
    {
        $keyInfo = static::$client->server()->getKeyInfo();

        if (!KeyLimitChecker::checkByType($keyInfo, KeyLimitChecker::LIMIT_CLIENTS, 2)) {
            $this->markTestSkipped('License does not allow to create more than 1 customer.');
        }

        static::$client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'customer-a',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
        ]);
        static::$client->customer()->create([
            'pname' => 'Mike Black',
            'login' => 'customer-b',
            'passwd' => PasswordProvider::STRONG_PASSWORD,
        ]);

        $customersInfo = static::$client->customer()->getAll();
        $this->assertIsArray($customersInfo);

        $customersCheck = array_filter($customersInfo, function ($value) {
            return $value->personalName === 'John Smith' || $value->personalName === 'Mike Black';
        });
        $this->assertCount(2, $customersCheck);

        static::$client->customer()->delete('login', 'customer-a');
        static::$client->customer()->delete('login', 'customer-b');
    }

    public function testGetAllEmpty()
    {
        $customersInfo = static::$client->customer()->getAll();
        $this->assertCount(0, $customersInfo);
    }

    public function testEnable()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        static::$client->customer()->disable('id', $customer->id);
        static::$client->customer()->enable('id', $customer->id);
        $customerInfo = static::$client->customer()->get('id', $customer->id);
        $this->assertTrue($customerInfo->enabled);

        static::$client->customer()->delete('id', $customer->id);
    }

    public function testDisable()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        static::$client->customer()->disable('id', $customer->id);
        $customerInfo = static::$client->customer()->get('id', $customer->id);
        $this->assertFalse($customerInfo->enabled);

        static::$client->customer()->delete('id', $customer->id);
    }

    public function testSetProperties()
    {
        $customer = static::$client->customer()->create($this->customerProperties);
        static::$client->customer()->setProperties('id', $customer->id, [
            'pname' => 'Mike Black',
            'email' => 'mike@black.com',
        ]);
        $customerInfo = static::$client->customer()->get('id', $customer->id);
        $this->assertEquals('Mike Black', $customerInfo->personalName);
        $this->assertEquals('mike@black.com', $customerInfo->email);

        static::$client->customer()->delete('id', $customer->id);
    }
}
