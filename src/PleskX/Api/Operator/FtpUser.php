<?php

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\FtpUser\Info;


class FtpUser extends \PleskX\Api\Operator {
	
    /**
     * @param $command
     * @param $field
     * @param $value
     * @return \PleskX\Api\XmlResponse
     */
    private function _get($command, $field, $value) {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild($command);

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        return $response;
    }
	
	
	/**
	 * @param string $ftpUser
	 * @param string $newPassword
	 * @return \PleskX\Api\Struct\FtpUser\Info
	 */
	public function updateFtpPassword( $ftpUser, $newPassword ) {
		$packet = $this->_client->getPacket();
		$set = $packet->addChild( $this->_wrapperTag )->addChild( 'set' );
		
		$filter = $set->addChild( 'filter' );
		$filter->addChild( 'name', $ftpUser );
		
		$values = $set->addChild( 'values' );
		$values->addChild( 'password', $newPassword );
		
		$response = $this->_client->request($packet);
		
		return new Info( $response );
	}
	
	
	 /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info[]
     */
    public function getAll($field = null, $value = null) {
        $response = $this->_get('get', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }
        return $items;
    }
}
