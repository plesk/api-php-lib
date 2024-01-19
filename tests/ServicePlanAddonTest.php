<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskXTest;

class ServicePlanAddonTest extends AbstractTestCase
{
    public function testGet()
    {
        $servicePlanAddon = static::createServicePlanAddon();
        $servicePlanAddonInfo = static::$client->servicePlanAddon()->get('id', $servicePlanAddon->id);

        $this->assertNotEmpty($servicePlanAddonInfo->name);
        $this->assertSame($servicePlanAddon->id, $servicePlanAddonInfo->id);
    }

    public function testGetAll()
    {
        static::createServicePlanAddon();
        static::createServicePlanAddon();
        static::createServicePlanAddon();

        $servicePlanAddons = static::$client->servicePlanAddon()->getAll();
        $this->assertIsArray($servicePlanAddons);
        $this->assertGreaterThan(2, count($servicePlanAddons));
        $this->assertNotEmpty($servicePlanAddons[0]->name);
    }

    public function testCreate()
    {
        $servicePlanAddon = static::createServicePlanAddon();
        $this->assertGreaterThan(0, $servicePlanAddon->id);

        static::$client->servicePlanAddon()->delete('id', $servicePlanAddon->id);
    }

    public function testDelete()
    {
        $servicePlanAddon = static::createServicePlanAddon();
        $result = static::$client->servicePlanAddon()->delete('id', $servicePlanAddon->id);

        $this->assertTrue($result);
    }

    public function testCreateComplex()
    {
        $properties = [
            'name' => 'Complex Service Plan Addon',
            'limits' => [
                'limit' => [
                    'name' => 'disk_space',
                    'value' => 1024 * 1024 * 1024, // 1 GB
                ],
            ],
        ];

        $servicePlanAddon = static::$client->servicePlanAddon()->create($properties);
        $this->assertGreaterThan(0, $servicePlanAddon->id);

        static::$client->servicePlanAddon()->delete('id', $servicePlanAddon->id);
    }
}
