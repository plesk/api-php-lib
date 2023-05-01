<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Site as Struct;

class Site extends \PleskX\Api\Operator
{
    public const PROPERTIES_HOSTING = 'hosting';

    public function create(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add');

        $infoGeneral = $info->addChild('gen_setup');
        foreach ($properties as $name => $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $infoGeneral->{$name} = (string) $value;
        }

        // set hosting properties
        if (isset($properties[static::PROPERTIES_HOSTING]) && is_array($properties[static::PROPERTIES_HOSTING])) {
            $hostingNode = $info->addChild('hosting')->addChild('vrt_hst');
            foreach ($properties[static::PROPERTIES_HOSTING] as $name => $value) {
                $propertyNode = $hostingNode->addChild('property');
                $propertyNode->name = $name;
                $propertyNode->value = $value;
            }
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response);
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
     * @return ?Struct\GeneralInfo
     */
    public function get(string $field, $value): ?Struct\GeneralInfo
    {
        $items = $this->getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items) ?: null;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\HostingInfo|null
     */
    public function getHosting(string $field, $value): ?Struct\HostingInfo
    {
        $items = $this->getItems(
            Struct\HostingInfo::class,
            'hosting',
            $field,
            $value,
            function (\SimpleXMLElement $node) {
                return isset($node->vrt_hst);
            }
        );

        return empty($items) ? null : reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll(): array
    {
        return $this->getItems(Struct\GeneralInfo::class, 'gen_info');
    }
}
