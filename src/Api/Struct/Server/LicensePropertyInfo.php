<?php

namespace PleskX\Api\Struct\Server;


/**
 * Struttura dati per le informazioni su una proprietà di una licenza
 */
class LicensePropertyInfo extends \PleskX\Api\Struct {
	
    /** @var string Nome della proprietà */
    public $name;
	
    /** @var string Valore della proprietà */
    public $value;

	
	/**
	 * @param \SimpleXMLElement $apiResponse
	 */
    public function __construct( $apiResponse ) {
		$this->_initScalarProperties( $apiResponse, [
            'name',
            'value',
        ]);
    }
}