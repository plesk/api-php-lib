<?php

namespace PleskX\Api\Struct\Server\License;

use PleskX\Api\Struct\Server\LicensePropertyInfo;


/**
 * Struttura dati per le informazioni su una licenza
 */
class keyInfo extends \PleskX\Api\Struct {
	
    /**
	 * Collezione di proprietà della licenza
	 * @var \PleskX\Api\Struct\Server\LicensePropertyInfo[] 
	 */
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