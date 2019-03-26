<?php

namespace PleskX\Api\Struct\Server;


/**
 * Struttura dati per le informazioni su una proprietà di una licenza
 */
class LicensePropertyInfo extends \PleskX\Api\Struct {
	
    /**
	 * Nome della proprietà
	 * @var string 
	 */
    public $name;
	
    /**
	 * Valore della proprietà
	 * @var string 
	 */
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