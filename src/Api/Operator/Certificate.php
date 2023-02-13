<?php
// Copyright 1999-2022. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Certificate as Struct;

class Certificate extends \PleskX\Api\Operator
{
    public function generate(array $properties): Struct\Info
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild($this->wrapperTag)->addChild('generate')->addChild('info');

        foreach ($properties as $name => $value) {
            $info->{$name} = $value;
        }

        $response = $this->client->request($packet);

        return new Struct\Info($response);
    }

	
    /**
	 * Ottiene tutti i certificati installati sul server per un determinato nome a dominio.
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info[]
     */
    public function getAll($field, $value )
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get-pool');

		$filterTag = $getTag->addChild('filter');
		$filterTag->addChild($field, $value);
		
        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
		
        $items = [];
        foreach ($response->xpath('//result/certificates') as $xmlResult) {
            $item = new Struct\PoolInfo($xmlResult->certificate);
            $items[] = $item;
        }
		
        return $items;
    }
	

	/**
	 * @param $properties
	 * @return Struct\InstallInfo
	 */
	public function install($properties)
	{
		$packet = $this->_client->getPacket();
		$install = $packet->addChild($this->_wrapperTag)->addChild('install');

		foreach ($properties as $name => $value) {
			if ($name == 'content') {
				$content = $install->addChild('content');

				foreach ($value as $contentKey => $contentValue) {
					$content->addChild($contentKey, $contentValue);
				}

				continue;
			}

			$install->addChild($name, $value);
		}

		$response = $this->_client->request($packet);
		return new Struct\InstallInfo($response);
	}
	

	/**
	 * @param $properties
	 * @return Struct\RemoveInfo
	 */
	public function remove($properties)
	{
		$packet = $this->_client->getPacket();
		$removeNode = $packet->addChild($this->_wrapperTag)->addChild('remove');

		foreach ($properties as $key => $value) {
			if( $key == 'filter' ) {
				$filterNode = $removeNode->addChild('filter');

				foreach( $value as $contentKey => $contentValue ) {
					$filterNode->addChild( $contentKey, $contentValue );
				}
				
				continue;
			}

			$removeNode->addChild($key, $value);
		}

		$response = $this->_client->request($packet);
		
		return new Struct\RemoveInfo( $response );
	}
}

