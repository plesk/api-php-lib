<?php

namespace PleskX\Api\Struct\Server;


/**
 * Struttura dati per la response di installazione licenza principale o aggiuntiva
 */
class LicenseInstall extends \PleskX\Api\Struct {
	
    /**
	 * Stato della licenza
	 * @var string 
	 */
    public $status;

    /**
	 * Codice di errore in caso di fallimento nell'installazione.
	 * NULL se la richiesta è andata a buon fine
	 * @var int
	 */
    public $error_code;

    /**
	 * Messaggio di errore in caso di fallimento nell'installazione
	 * @var string
	 */
    public $error_message;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
        $this->_initScalarProperties( $apiResponse, [
            [ 'status'  => 'status'],
        ]);
		
		if( isset( $apiResponse->errcode ) ) {
			$this->error_code = $apiResponse->errcode;
		}
		
		$this->error_message = '';
		if( isset( $apiResponse->errtext ) ) {
			$this->error_code = $apiResponse->errtext;
		}
    }
}