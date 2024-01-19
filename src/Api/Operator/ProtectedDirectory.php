<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\ProtectedDirectory as Struct;

class ProtectedDirectory extends Operator
{
    protected string $wrapperTag = 'protected-dir';

    public function add(string $name, int $siteId, string $header = ''): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add');

        $info->addChild('site-id', (string) $siteId);
        $info->addChild('name', $name);
        $info->addChild('header', $header);

        return new Struct\Info($this->client->request($packet));
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
     * @return Struct\DataInfo
     */
    public function get(string $field, $value): Struct\DataInfo
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\DataInfo[]
     */
    public function getAll(string $field, $value): array
    {
        $response = $this->getBy('get', $field, $value);
        $items = [];
        foreach ((array) $response->xpath('//result/data') as $xmlResult) {
            if (!$xmlResult) {
                continue;
            }
            $items[] = new Struct\DataInfo($xmlResult);
        }

        return $items;
    }

    /**
     * @param Struct\Info $protectedDirectory
     * @param string $login
     * @param string $password
     *
     * @return Struct\UserInfo
     * @psalm-suppress UndefinedPropertyAssignment
     */
    public function addUser($protectedDirectory, $login, $password)
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('add-user');

        $info->{'pd-id'} = (string) $protectedDirectory->id;
        $info->login = $login;
        $info->password = $password;

        return new Struct\UserInfo($this->client->request($packet));
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function deleteUser($field, $value)
    {
        return $this->deleteBy($field, $value, 'delete-user');
    }

    /**
     * @param string $command
     * @param string $field
     * @param int|string $value
     *
     * @return \PleskX\Api\XmlResponse
     */
    private function getBy(string $command, string $field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild($command);

        $filterTag = $getTag->addChild('filter');
        $filterTag->{$field} = (string) $value;

        return $this->client->request($packet, Client::RESPONSE_FULL);
    }
}
