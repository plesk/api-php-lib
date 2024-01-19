<?php
// Copyright 1999-2024. WebPros International GmbH.

namespace PleskX\Api;

class Operator
{
    protected string $wrapperTag = '';
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        if ('' === $this->wrapperTag) {
            $classNameParts = explode('\\', get_class($this));
            $this->wrapperTag = end($classNameParts);
            $this->wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $this->wrapperTag));
        }
    }

    /**
     * Perform plain API request.
     *
     * @param string|array $request
     * @param int $mode
     *
     * @return XmlResponse
     */
    public function request($request, $mode = Client::RESPONSE_SHORT): XmlResponse
    {
        $wrapperTag = $this->wrapperTag;

        if (is_array($request)) {
            $request = [$wrapperTag => $request];
        } elseif (preg_match('/^[a-z]/', $request)) {
            $request = "$wrapperTag.$request";
        } else {
            $request = "<$wrapperTag>$request</$wrapperTag>";
        }

        return $this->client->request($request, $mode);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param string $deleteMethodName
     *
     * @return bool
     */
    protected function deleteBy(string $field, $value, string $deleteMethodName = 'del'): bool
    {
        $response = $this->request([
            $deleteMethodName => [
                'filter' => [
                    $field => $value,
                ],
            ],
        ]);

        return 'ok' === (string) $response->status;
    }

    /**
     * @param string $structClass
     * @param string $infoTag
     * @param string|null $field
     * @param int|string|null $value
     * @param callable|null $filter
     *
     * @return array
     */
    protected function getItems($structClass, $infoTag, $field = null, $value = null, callable $filter = null): array
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = (string) $value;
        }

        $getTag->addChild('dataset')->addChild($infoTag);

        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ((array) $response->xpath('//result') as $xmlResult) {
            if (!$xmlResult || !isset($xmlResult->data) || !isset($xmlResult->data->$infoTag)) {
                continue;
            }
            if (!is_null($filter) && !$filter($xmlResult->data->$infoTag)) {
                continue;
            }
            /** @psalm-suppress InvalidStringClass */
            $item = new $structClass($xmlResult->data->$infoTag);
            if (isset($xmlResult->id) && property_exists($item, 'id')) {
                $item->id = (int) $xmlResult->id;
            }
            $items[] = $item;
        }

        return $items;
    }
}
