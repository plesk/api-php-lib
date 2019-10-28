<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskXTest;

class ServicePlanTest extends TestCase
{
    public function testGet()
    {
        $servicePlan = static::_createServicePlan();
        $servicePlanInfo = static::$_client->servicePlan()->get('id', $servicePlan->id);
        $this->assertNotEmpty($servicePlanInfo->name);
        $this->assertSame($servicePlan->id, $servicePlanInfo->id);

        static::$_client->servicePlan()->delete('id', $servicePlan->id);
    }

    public function testGetAll()
    {
        static::_createServicePlan();
        static::_createServicePlan();
        static::_createServicePlan();

        $servicePlans = static::$_client->servicePlan()->getAll();
        $this->assertIsArray($servicePlans);
        $this->assertGreaterThan(2, count($servicePlans));
        $this->assertNotEmpty($servicePlans[0]->name);
    }

    public function testCreateServicePlan()
    {
        $servicePlan = static::_createServicePlan();
        $this->assertGreaterThan(0, $servicePlan->id);

        static::$_client->servicePlan()->delete('id', $servicePlan->id);
    }

    public function testDelete()
    {
        $servicePlan = static::_createServicePlan();
        $result = static::$_client->servicePlan()->delete('id', $servicePlan->id);

        $this->assertTrue($result);
    }

    public function testRequestCreateServicePlan()
    {
        $request = [
            'add' => [
                'name' => 'Service Plan Full Test',
                'limits' => [
                    'overuse' => 'block',
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
                'performance' => [
                    'bandwidth' => 1000,
                    'max_connections' => 20,
                ],
            ],
        ];

        $servicePlan = static::$_client->servicePlan()->request($request);
        $this->assertGreaterThan(0, $servicePlan->id);

        static::$_client->servicePlan()->delete('id', $servicePlan->id);
    }
}
