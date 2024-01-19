<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\ServicePlan as Struct;

class ServicePlan extends \PleskX\Api\Operator
{
    public function create(array $properties): Struct\Info
    {
        $response = $this->request(['add' => $properties]);

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
     * @return Struct\Info
     */
    public function get(string $field, $value): Struct\Info
    {
        $items = $this->getBy($field, $value);

        return reset($items);
    }

    /**
     * @return Struct\Info[]
     */
    public function getAll(): array
    {
        return $this->getBy();
    }

    /**
     * @param string|null $field
     * @param int|string|null $value
     *
     * @return Struct\Info[]
     */
    private function getBy($field = null, $value = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, (string) $value);
        }

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if (!$xmlResult) {
                continue;
            }
            $items[] = new Struct\Info($xmlResult);
        }

        return $items;
    }
}
