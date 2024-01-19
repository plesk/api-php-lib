<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{
    public function create(array $properties, array $preferences = []): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('create');

        if (count($preferences) > 0) {
            $prefs = $info->addChild('pref');

            foreach ($preferences as $key => $value) {
                $prefs->addChild($key, is_bool($value) ? ($value ? '1' : '0') : $value);
            }
        }

        $info->addChild('site-id', $properties['site-id']);
        $info->addChild('name', $properties['name']);

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
        return $this->deleteBy($field, $value, 'delete');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get(string $field, $value): Struct\GeneralInfo
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo[]
     */
    public function getAll($field = null, $value = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = (string) $value;
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if (!$xmlResult) {
                continue;
            }
            if (!is_null($xmlResult->info)) {
                $item = new Struct\GeneralInfo($xmlResult->info);
                $items[] = $item;
            }
        }

        return $items;
    }
}
