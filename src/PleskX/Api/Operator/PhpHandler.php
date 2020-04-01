<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;


class PhpHandler extends \PleskX\Api\Operator
{

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