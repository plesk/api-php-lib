<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Database as Struct;

class Database extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     *
     * @return Struct\Info
     */
    public function create($properties)
    {
        return new Struct\Info($this->process('add-db', $properties));
    }

    /**
     * @param array $properties
     *
     * @return Struct\UserInfo
     */
    public function createUser($properties)
    {
        return new Struct\UserInfo($this->process('add-db-user', $properties));
    }

    /**
     * @param string $command
     * @param array $properties
     *
     * @return \PleskX\Api\XmlResponse
     */
    private function process($command, array $properties)
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

    /**
     * @param array $properties
     *
     * @return bool
     */
    public function updateUser(array $properties)
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
    public function get($field, $value)
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
    public function getUser($field, $value)
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
    public function getAll($field, $value)
    {
        $response = $this->getBy('get-db', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }

        return $items;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo[]
     */
    public function getAllUsers($field, $value)
    {
        $response = $this->getBy('get-db-users', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\UserInfo($xmlResult);
        }

        return $items;
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

        return $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->deleteBy($field, $value, 'del-db');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function deleteUser($field, $value)
    {
        return $this->deleteBy($field, $value, 'del-db-user');
    }
}
