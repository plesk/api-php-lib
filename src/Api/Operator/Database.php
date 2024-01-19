<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Database as Struct;
use PleskX\Api\XmlResponse;

class Database extends \PleskX\Api\Operator
{
    public function create(array $properties): Struct\Info
    {
        return new Struct\Info($this->process('add-db', $properties));
    }

    public function createUser(array $properties): Struct\UserInfo
    {
        return new Struct\UserInfo($this->process('add-db-user', $properties));
    }

    private function process(string $command, array $properties): XmlResponse
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild($command);

        foreach ($properties as $name => $value) {
            if (false !== strpos($value, '&')) {
                $info->$name = $value;
                continue;
            }
            $info->{$name} = $value;
        }

        return $this->client->request($packet);
    }

    public function updateUser(array $properties): bool
    {
        $response = $this->process('set-db-user', $properties);

        return 'ok' === (string) $response->status;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info
     */
    public function get(string $field, $value): Struct\Info
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo
     */
    public function getUser(string $field, $value): Struct\UserInfo
    {
        $items = $this->getAllUsers($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info[]
     */
    public function getAll(string $field, $value): array
    {
        $response = $this->getBy('get-db', $field, $value);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if ($xmlResult) {
                $items[] = new Struct\Info($xmlResult);
            }
        }

        return $items;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo[]
     */
    public function getAllUsers(string $field, $value): array
    {
        $response = $this->getBy('get-db-users', $field, $value);
        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if ($xmlResult) {
                $items[] = new Struct\UserInfo($xmlResult);
            }
        }

        return $items;
    }

    /**
     * @param string $command
     * @param string $field
     * @param int|string $value
     *
     * @return XmlResponse
     */
    private function getBy(string $command, string $field, $value): XmlResponse
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild($command);

        $filterTag = $getTag->addChild('filter');
        $filterTag->{$field} = (string) $value;

        return $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete(string $field, $value): bool
    {
        return $this->deleteBy($field, $value, 'del-db');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function deleteUser(string $field, $value): bool
    {
        return $this->deleteBy($field, $value, 'del-db-user');
    }
}
