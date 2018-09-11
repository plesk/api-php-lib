<?php

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\FtpUser\Info as Struct;


class FtpUser extends \PleskX\Api\Operator {
	
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
		
		return new Struct( $response );
	}
}
