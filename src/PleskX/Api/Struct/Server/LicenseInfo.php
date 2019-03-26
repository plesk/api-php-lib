<?php

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct\Server\License\keyInfo;


/**
 * Struttura dati per le informazioni sulla licenza installata
 */
class LicenseInfo extends \PleskX\Api\Struct {
	
    /**
	 * Stato della licenza
	 * @var string 
	 */
    public $status;

    /**
	 * Licenza
	 * @var \PleskX\Api\Struct\Server\License\keyInfo
	 */
    public $key;

	
	/**
	 * @param \SimpleXMLElement $apiResponse Oggetto xml a partire da <result>
	 */
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties( $apiResponse, [
            'status',
        ]);
		
		if( isset( $apiResponse->key ) ) {
			$this->key = new keyInfo( $apiResponse->key );
		}  
    }
}