<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends Operator
{
    public function getPermissionDescriptor(): Struct\PermissionDescriptor
    {
        $response = $this->request('get-permission-descriptor.filter');

        return new Struct\PermissionDescriptor($response);
    }

    public function getLimitDescriptor(): Struct\LimitDescriptor
    {
        $response = $this->request('get-limit-descriptor.filter');

        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor(): Struct\PhysicalHostingDescriptor
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');

        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\PhpSettings
     */
    public function getPhpSettings(string $field, $value): Struct\PhpSettings
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $getTag->addChild('filter')->addChild($field, (string) $value);
        $getTag->addChild('dataset')->addChild('php-settings');

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        return new Struct\PhpSettings($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Limits
     */
    public function getLimits(string $field, $value): Struct\Limits
    {
        $items = $this->getItems(Struct\Limits::class, 'limits', $field, $value);

        return reset($items);
    }

    /**
     * @param array $properties
     * @param array|null $hostingProperties
     * @param string $planName
     *
     * @return Struct\Info
     */
    public function create(array $properties, array $hostingProperties = null, string $planName = ''): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add');

        $infoGeneral = $info->addChild('gen_setup');
        foreach ($properties as $name => $value) {
            if (is_array($value)) {
                continue;
            } else {
                $infoGeneral->addChild($name, (string) $value);
            }
        }

        if ($hostingProperties) {
            $infoHosting = $info->addChild('hosting')->addChild('vrt_hst');
            foreach ($hostingProperties as $name => $value) {
                $property = $infoHosting->addChild('property');
                $property->name = $name;
                $property->value = $value;
            }

            if (isset($properties['ip_address'])) {
                foreach ((array) $properties['ip_address'] as $ipAddress) {
                    $infoHosting->addChild('ip_address', $ipAddress);
                }
            }
        }

        if ('' !== $planName) {
            $info->addChild('plan-name', $planName);
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response, $properties['name'] ?? '');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete(string $field, $value): bool
    {
        return $this->deleteBy($field, $value);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get(string $field, $value): Struct\GeneralInfo
    {
        $items = $this->getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll(): array
    {
        return $this->getItems(Struct\GeneralInfo::class, 'gen_info');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\DiskUsage
     */
    public function getDiskUsage(string $field, $value): Struct\DiskUsage
    {
        $items = $this->getItems(Struct\DiskUsage::class, 'disk_usage', $field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function enable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 0]);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function disable(string $field, $value): bool
    {
        return $this->setProperties($field, $value, ['status' => 1]);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param array $properties
     *
     * @return bool
     */
    public function setProperties(string $field, $value, array $properties): bool
    {
        $packet = $this->client->getPacket();
        $setTag = $packet->addChild($this->wrapperTag)->addChild('set');
        $setTag->addChild('filter')->addChild($field, (string) $value);
        $genInfoTag = $setTag->addChild('values')->addChild('gen_setup');
        foreach ($properties as $property => $propertyValue) {
            $genInfoTag->addChild($property, (string) $propertyValue);
        }

        $response = $this->client->request($packet);

        return 'ok' === (string) $response->status;
    }
}
