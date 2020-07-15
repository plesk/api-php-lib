<?php
// Copyright 1999-2020. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\PhpHandler\Info;

class PhpHandler extends Operator
{
    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Info
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');
        $filterTag = $getTag->addChild('filter');

        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);
        $xmlResult = $response->xpath('//result')[0];

        return new Info($xmlResult);
    }

    /**
     * @param string|null $field
     * @param int|string $value
     *
     * @return Info[]
     */
    public function getAll($field = null, $value = null)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $item = new Info($xmlResult);
            $items[] = $item;
        }

        return $items;
    }

	/**
	 * Restituisce tutte le subscriptions (servizi attivati) presenti sul server.
	 * Tali subscription potrebbero anche essere in uno stato non attivo
	 * @return Struct\GeneralInfo[]
	 */
	public function getHandler( $handlerId ) {
		$packet = $this->_client->getPacket();
		$getTag = $packet->addChild($this->_wrapperTag)->addChild('get');
		
		$filterTag = $getTag->addChild('filter');
		$filterTag->addChild('id', $handlerId);
		$response = $this->_client->request( $packet );
		
		$item = [];
		
		$item['id'] = $response->getValue( '//id' );
		$item['display-name'] = $response->getValue( '//display-name' );
		$item['full-version'] = $response->getValue( '//full-version' );
		$item['version'] = $response->getValue( '//version' );
		$item['type'] = $response->getValue( '//type' );
		
		return $item;
	}
}
