<?php

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct\Server\License\keyInfo;


/**
 * Struttura dati per le informazioni sulla licenza installata
 */
class LicenseInfo extends \PleskX\Api\Struct {
	
    /** @var string Stato della licenza */
    public $status;

    /** @var \PleskX\Api\Struct\Server\License\keyInfo Licenza */
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