<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class ServicePlanTest extends AbstractTestCase
{
    public function testGet()
    {
        $servicePlan = static::createServicePlan();
        $servicePlanInfo = static::$client->servicePlan()->get('id', $servicePlan->id);
        $this->assertNotEmpty($servicePlanInfo->name);
        $this->assertSame($servicePlan->id, $servicePlanInfo->id);

        static::$client->servicePlan()->delete('id', $servicePlan->id);
    }

    public function testGetAll()
    {
        static::createServicePlan();
        static::createServicePlan();
        static::createServicePlan();

        $servicePlans = static::$client->servicePlan()->getAll();
        $this->assertIsArray($servicePlans);
        $this->assertGreaterThan(2, count($servicePlans));
        $this->assertNotEmpty($servicePlans[0]->name);
    }

    public function testCreateServicePlan()
    {
        $servicePlan = static::createServicePlan();
        $this->assertGreaterThan(0, $servicePlan->id);

        static::$client->servicePlan()->delete('id', $servicePlan->id);
    }

    public function testDelete()
    {
        $servicePlan = static::createServicePlan();
        $result = static::$client->servicePlan()->delete('id', $servicePlan->id);

        $this->assertTrue($result);
    }

    public function testCreateComplexServicePlan()
    {
        $properties = [
            'name' => 'Complex Service Plan',
            'limits' => [
                'overuse' => 'block',
                'limit' => [
                    'name' => 'disk_space',
                    'value' => 1024 * 1024 * 1024, // 1 GB
                ],
            ],
            'preferences' => [
                'stat' => 6,
                'maillists' => 'true',
            ],
            'hosting' => [
                'property' => [
                    [
                        'name' => 'ftp_quota',
                        'value' => '-1',
                    ],
                    [
                        'name' => 'ssl',
                        'value' => 'true',
                    ],
                ],
            ],
        ];

        $servicePlan = static::$client->servicePlan()->create($properties);
        $this->assertGreaterThan(0, $servicePlan->id);

        static::$client->servicePlan()->delete('id', $servicePlan->id);
    }
}
