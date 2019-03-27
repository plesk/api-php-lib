<?php

namespace PleskX\Api\Struct\Server;


/**
 * Struttura dati per la response di installazione licenza principale o aggiuntiva
 */
class LicenseInstall extends \PleskX\Api\Struct {
	
    /** @var string Stato della licenza */
    public $status;

    /** @var int Codice di errore in caso di fallimento nell'installazione (NULL se la richiesta è andata a buon fine) */
    public $error_code;

    /** @var string Messaggio di errore in caso di fallimento nell'installazione */
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