<?php

namespace PleskX\Api\Struct\Server\License;

use PleskX\Api\AbstractStruct;
use PleskX\Api\Struct\Server\LicensePropertyInfo;


/**
 * Struttura dati per le informazioni su una licenza
 */
class keyInfo extends AbstractStruct {
	
    /** @var \PleskX\Api\Struct\Server\LicensePropertyInfo[] Collezione di proprietà della licenza */
    public $properties;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
		$this->properties = [];
		
		if( isset( $apiResponse->property ) ) {
			foreach( $apiResponse->property as $property ) {
				$this->properties[] = new LicensePropertyInfo( $property );
			}
		}
    }
}