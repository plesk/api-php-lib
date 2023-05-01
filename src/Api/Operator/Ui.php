<?php
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ui as Struct;

class Ui extends \PleskX\Api\Operator
{
    public function getNavigation(): array
    {
        $response = $this->request('get-navigation');

        return unserialize(base64_decode($response->navigation));
    }

    public function createCustomButton(string $owner, array $properties): int
    {
        $packet = $this->client->getPacket();
        $buttonNode = $packet->addChild($this->wrapperTag)->addChild('create-custombutton');
        $buttonNode->addChild('owner')->addChild($owner);
        $propertiesNode = $buttonNode->addChild('properties');

        foreach ($properties as $name => $value) {
            $propertiesNode->{$name} = $value;
        }

        $response = $this->client->request($packet);

        return (int) $response->id;
    }

    public function getCustomButton(int $id): Struct\CustomButton
    {
        $response = $this->request("get-custombutton.filter.custombutton-id=$id");

        return new Struct\CustomButton($response);
    }

    public function deleteCustomButton(int $id): bool
    {
        return $this->deleteBy('custombutton-id', $id, 'delete-custombutton');
    }
}
