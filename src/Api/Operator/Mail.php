<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\Mail as Struct;

class Mail extends Operator
{
    /**
     * @param string $name
     * @param int $siteId
     * @param bool $mailbox
     * @param string $password
     *
     * @return Struct\Info
     */
    public function create($name, $siteId, $mailbox = false, $password = '')
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('create');

        $filter = $info->addChild('filter');
        $filter->addChild('site-id', (string) $siteId);
        $mailname = $filter->addChild('mailname');
        $mailname->addChild('name', $name);
        if ($mailbox) {
            $mailname->addChild('mailbox')->addChild('enabled', 'true');
        }
        if (!empty($password)) {
            $mailname->addChild('password')->value = $password;
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response->mailname);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param int $siteId
     *
     * @return bool
     */
    public function delete(string $field, $value, $siteId): bool
    {
        $packet = $this->client->getPacket();
        $filter = $packet->addChild($this->wrapperTag)->addChild('remove')->addChild('filter');

        $filter->addChild('site-id', (string) $siteId);
        $filter->{$field} = (string) $value;

        $response = $this->client->request($packet);

        return 'ok' === (string) $response->status;
    }

    /**
     * @param string $name
     * @param int $siteId
     *
     * @return Struct\GeneralInfo
     */
    public function get($name, $siteId)
    {
        $items = $this->getAll($siteId, $name);

        return reset($items);
    }

    /**
     * @param int $siteId
     * @param string|null $name
     *
     * @return Struct\GeneralInfo[]
     */
    public function getAll($siteId, $name = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get_info');

        $filterTag = $getTag->addChild('filter');
        $filterTag->addChild('site-id', (string) $siteId);
        if (!is_null($name)) {
            $filterTag->addChild('name', $name);
        }

        $response = $this->client->request($packet, Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            if (!isset($xmlResult->mailname)) {
                continue;
            }
            $item = new Struct\GeneralInfo($xmlResult->mailname);
            $items[] = $item;
        }

        return $items;
    }
}
