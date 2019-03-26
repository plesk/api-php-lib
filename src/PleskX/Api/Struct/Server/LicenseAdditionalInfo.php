<?php

namespace PleskX\Api\Struct\Server;

use PleskX\Api\Struct\Server\LicenseAdditional\keyAdditionalInfo;


/**
 * Struttura dati per le informazioni su una licenza aggiuntiva
 */
class LicenseAdditionalInfo extends \PleskX\Api\Struct {
	
    /**
	 * Stato della licenza
	 * @var string 
	 */
    public $status;

    /**
	 * Codice di errore in caso di fallimento della richiesta
	 * NULL se la richiesta è andata a buon fine
	 * @var int
	 */
    public $error_code;

    /**
	 * Messaggio di errore in caso di fallimento della richiesta
	 * @var string
	 */
    public $error_message;
	
    /**
	 * Licenza aggiuntiva
	 * @var \PleskX\Api\Struct\Server\License\keyAdditionalInfo
	 */
    public $key;

	
	/**
	 * @param \SimpleXMLElement $apiResponse Oggetto xml a partire da <result>
	 */
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties( $apiResponse, [
            'status',
        ]);
		
		if( isset( $apiResponse->errcode ) ) {
			$this->error_code = $apiResponse->errcode;
		}
		
		$this->error_message = '';
		if( isset( $apiResponse->errtext ) ) {
			$this->error_code = $apiResponse->errtext;
		}
		
		if( isset( $apiResponse->key_info ) ) {
			$this->key = new keyAdditionalInfo( $apiResponse->key_info );
		}
    }
}